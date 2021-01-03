<?php

namespace App\Core\Sale;

use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\AuctionsCartType;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\View\OrderStatusViewModel;
use App\Core\Traits\Sale\CartTrait;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Helpers\TTL;
use App\Helpers\UserHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixBasketItemProperty;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\PaidService;
use App\Models\Catalog\ProductFactory;
use App\Models\Catalog\ProductInterface;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\Jewelry\JewelrySku;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\BasketItemBase;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use CSaleUser;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;

Loader::IncludeModule('sale');

/**
 * Класс для работы с корзиной
 * Class Basket
 * @package App\Core\Sale
 */
class UserCart extends Basket
{
    /** Трейт для работы с корзиной */
    use CartTrait;

    /** @var static[] $instance - Экземпляр класса */
    private static $instance = [];

    /** @var Basket $cart - Корзина пользователя */
    private $cart;

    /** @var CartTypeInterface */
    private $cartType;

    /** @var bool $isCartChanged - Флаг, указывающий на то, что корзина изменялась  */
    private $isCartChanged = false;

    /**
     * UserCart constructor.
     *
     * @param CartTypeInterface $cartType Тип корзины
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function __construct(CartTypeInterface $cartType)
    {
        parent::__construct();
        $this->cartType = $cartType;

        $this->loadCart();
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone() {}

    /**
     * Prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup() {}

    /**
     * Получает экземпляр текущего класса
     *
     * @param CartTypeInterface $cartType Тип корзины
     *
     * @return static
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public static function getInstance(CartTypeInterface $cartType)
    {
        $key = static::class . '|' . get_class($cartType);
        if (!isset(self::$instance[$key])) {
            self::$instance[$key] = new static($cartType);
        }

        return self::$instance[$key];
    }

    /**
     * @return CartTypeInterface
     */
    public function getCartType(): CartTypeInterface
    {
        return $this->cartType;
    }

    /**
     * Проверяет, изменялась ли корзина
     *
     * @return bool
     */
    public function isCartChanged(): bool
    {
        return $this->isCartChanged;
    }

    /**
     * @return void
     */
    private function loadCart(): void
    {
        $cart = static::getUserCart($this->getCartType(), $this->getFUserId());

        $basketItemsIds = [];
        /** @var BasketItem $item */
        foreach ($cart->getBasketItems() as $item) {
            try {
                $basketItemsIds[] = $item->getId();
            } catch (Exception $exception) {
                // ignore
            }
        }
        $basketItemsIds = array_filter($basketItemsIds);

        $this->loadBasketItemsWithPaidServices($basketItemsIds);

        /** @var bool $isAuctionCart - аукционный ли тип корзины */
        $isAuctionCart = $this->getCartType() instanceof AuctionsCartType;

        $isAnyBasketItemDeleted = false;

        /** @var BasketItem $item */
        foreach ($cart->getBasketItems() as $offset => $item) {
            try {
                $id = $item->getId();
            } catch (Exception $exception) {
                continue;
            }

            $delItem = false;
            $bitrixBasketItem = $this->getBitrixBasketItemById($id);
            $product = $bitrixBasketItem ? $bitrixBasketItem->getProduct() : null;
            if ($isAuctionCart) {
                // Если аукционная корзина, то удаляем все, кроме камней, привязанных к лотам
                if (!($product instanceof Diamond) || !($product->auctionLot || $product->auctionPBLot)) {
                    $delItem = true;
                }
            } elseif (
                $product instanceof Diamond
                && ($product->auctionLot || $product->auctionPBLot || !$product->isAvailableForSelling())
            ) {
                // Если обычная корзина, то удаляем все камни, привязанные к аукционным лотам
                $delItem = true;
            } elseif (
                ($product instanceof JewelryConstructorReadyProduct || $product instanceof Jewelry || $product instanceof JewelrySku)
                && !$product->isAvailableForSelling()) {
                // Если в корзине есть недоступный к покупке товар из конструктора, то удаляем его
                $delItem = true;
            }

            if ($delItem) {
                // Если из корзины удалялись элементы, переключаем значение флага
                $this->isCartChanged = true;
                $cart->offsetUnset($offset);
                $this->forgetBitrixBasketItem($id);
                $isAnyBasketItemDeleted = true;
            }
        }

        // Проверяем, удалялась ли из корзины забытая услуга или товар
        $isAnyBasketItemDeleted = $this->clearAlonePaidService($cart) || $isAnyBasketItemDeleted;

        // Если что-то удалялось, сохраняем корзину
        if ($isAnyBasketItemDeleted) {
            /** @var \Bitrix\Main\ORM\Data\Result $result */
            $result = $cart->save();

            if (!$result->isSuccess()) {
                logger()->error('Ошибка при удалении товаров из корзины'.implode("\n", $result->getErrorMessages()));
            }
        }

        $this->cart = $cart;
    }

    /**
     * Удаляет из корзины платные услуги, для которых в корзине не осталось товаров
     *
     * @param Basket $cart
     *
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     *
     * @return bool
     */
    private function clearAlonePaidService(Basket $cart): bool
    {
        $isAnyBasketItemDeleted = false;
        $paidServicesToParentBasketItemIdsMap = [];

        // Перебираем элементы в корзине пользователя, отбираем только услуги
        /** @var BasketItem $item */
        foreach ($cart->getBasketItems() as $offset => $item) {
            if (!$this->isPaidService($item)) {
                continue;
            }

            $productXmlId = $item->getField('PRODUCT_XML_ID');

            // В свойстве "PRODUCT_XML_ID" платной услуги хранится Id элемента корзины, которому услуга принадлежит
            // "PRODUCT_XML_ID" имеет значение вида owner:12345
            if (!$productXmlId || !preg_match('/^[^:]+:([\d]+)$/i', $productXmlId, $matches)) {
                continue;
            }

            $paidServicesToParentBasketItemIdsMap[$offset] = (int)$matches[1];
        }

        // Перебираем отобранные услуги, проверяем что в корзине есть связанные с ними товары
        foreach ($paidServicesToParentBasketItemIdsMap as $offset => $parentBasketItemId) {

            $parentFound = false;

            // Перебираем элементы в корзине пользователя, отбираем только товары
            foreach ($cart->getBasketItems() as $item) {
                if ($this->isPaidService($item)) {
                    continue;
                }

                try {
                    $id = $item->getId();
                } catch (Exception $exception) {
                    continue;
                }

                if ($id == $parentBasketItemId) {
                    $parentFound = true;
                }
            }

            // Если для для услуги не найден товар в корзине - удаляем услугу
            if (!$parentFound) {
                $item = $cart->getItemByIndex($offset);

                try {
                    $id = $item->getId();
                    $cart->offsetUnset($offset);
                    $this->forgetBitrixBasketItem($id);
                    $isAnyBasketItemDeleted = true;
                } catch (Exception $exception) {
                    logger()->error('Ошибка при удалении товаров из корзины'.implode("\n", $exception->getMessage()));
                }
            }
        }

        return $isAnyBasketItemDeleted;
    }

    /**
     * Возвращает корзину пользователя в соответствии с типом корзины
     * Для аукционного типа корзины не будет товаров не из аукциона, для других типов - аукционных.
     *
     * @return Basket
     */
    public function getCart(): Basket
    {
        return $this->cart;
    }

    /**
     * Обновляет данные по корзине
     *
     * @return void
     */
    public function refreshBasketInfo(): void
    {
        $this->loadCart();
    }

    /**
     * Добавляем товар в корзину
     *
     * @param ProductInterface $product
     * @param Basket|null $cart
     * @param float|null $price
     * @param CartTypeInterface $cartType
     *
     * @return int
     *
     * @throws Exception
     */
    public static function addProduct(
        ProductInterface $product,
        CartTypeInterface $cartType,
        Basket $cart = null,
        float $price = null
    ): int {
        $productId = $product->getID();
        if (!$cart) {
            $cart = static::getUserCart($cartType);
        }

        $item = $item = $cart->getExistsItem('catalog', $productId);
        if (!$item) {
            $item = $cart->createItem('catalog', $productId);

            $item->setFields([
                'QUANTITY' => 1,
                'CURRENCY' => $cartType->getCurrency()->getSymCode(),
                'LID' => $cartType->getSiteId(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                'PRODUCT_XML_ID' => $product->getProductTypeCode().':' . $productId,
                'PRICE' => $price ?: $product->getPriceForCart(),
                'CUSTOM_PRICE' => ($price || !UserHelper::isLegalEntity()) ? 'Y' : 'N',
            ]);
        }

        // Если в корзину добавляется товар из конструктора, заполняем свойства заказа для отображения в админке
        if ($product instanceof JewelryConstructorReadyProduct) {
            static::applyConstructorBasketProperties($product, $item);
        }

        $result = $cart->save();
        if (!$result->isSuccess()) {
            logger()->error('Ошибка при добавлении товара в корзину'.implode("\n", $result->getErrorMessages()));
            //throw new Exception('Ошибка при добавлении товара в корзину');
        }

        return $item->getId();
    }

    /**
     * Получаем корзину
     *
     * @param CartTypeInterface $cartType Тип корзины
     * @param int|null $fUserId
     *
     * @return Basket
     */
    public static function getUserCart(CartTypeInterface $cartType, int $fUserId = null): Basket
    {
        /** @var Basket $basket */
        $basket = parent::loadItemsForFUser($fUserId ?? Fuser::getId(), $cartType->getSiteId());

        return $basket;
    }

    /**
     * Возвращает корзину пользователя по его id
     *
     * @param int $userId идентификатор пользователя в бд
     *
     * @return Basket
     */
    public static function getUserCartByUserId(int $userId): Basket
    {
        $result = (new CSaleUser())->GetList(['USER_ID' => $userId]);

        return static::getUserCart(new AuctionsCartType(), $result['ID']);
    }

    /**
     * Удаляем платные услуги из корзины для определённого товара.
     *
     * @param int|null $productId - Идентификатор удаляемого товара (в корзине)
     * @param string $productXmlId
     * @param Order|null $order - заказ
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public static function removeServiceFromCart(int $productId, string $productXmlId, $order = null): void
    {
        /** @var Basket $cart */
        /** @var BasketItem $item */
        if ($order) {
            $cart = $order->getBasket();
        } else {
            $cart = static::getUserCart((new DefaultCartType()));
        }
        foreach ($cart->getBasketItems() as $item) {
            if (($productId == $item->getProductId()) && ($productXmlId == $item->getField('PRODUCT_XML_ID'))) {
                $item->delete();
            }
        }
        $cart->save();
    }

    /**
     * Удаляем товары из корзины. И связанные с ним услуги. Если не указан идентификатор товара, то очистится вся корзина
     *
     * @param int|null $productId - Идентификатор удаляемого товара
     *
     * @return void
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @throws \Exception
     */
    public static function removeFromCart(?int $productId): void
    {
        $productId = (int)$productId;
        $defaultCartType = new DefaultCartType();

        /** @var Basket $cart */
        $bitrixCart = static::getUserCart($defaultCartType);

        $cart = static::getInstance($defaultCartType);

        /** @var BitrixBasketItem|null $basketItem - Товар в корзине */
        $basketItem = $cart->getBasketItems()->first(function (BitrixBasketItem $item) use ($productId) {
            return $item->getProductId() === $productId;
        });

        /** @var BasketItem[] $items */
        $items = $bitrixCart->getBasketItems();
        foreach ($items as $item) {
            if (!$productId || ($productId === (int)$item->getProductId())) {

                // Если удаляем услугу, то в $basketItem будет null
                if ($basketItem) {
                    // Удаляем все связанные услуги
                    $product = $basketItem->getProduct();
                    if ($product) {
                        $engraving = $cart->getEngravings()[$product->getID()];
                        if ($engraving) {
                            BitrixBasketItem::getById($engraving->getId())->delete();
                        }

                        $certificate = $cart->getCertificates()[$product->getID()];
                        if ($certificate) {
                            BitrixBasketItem::getById($certificate->getId())->delete();
                        }
                    }
                }

                // Удаляем продукт из корзины
                $item->delete();
            }
        }

        $bitrixCart->save();
    }

    /**
     * Добавляет дополнительную платную услугу для позиции корзины.
     *
     * @param BasketItem $dest
     * @param int|PaidService $service
     * @param int $quantity
     * @param array $props
     * @param ProductInterface|null $product
     *
     * @param float|null $price
     * @return BasketItem
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\Db\SqlQueryException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public static function addPaidService(
        BasketItem $dest,
        $service,
        int $quantity = 1,
        array $props = [],
        ProductInterface $product = null,
        float $price = null
    ): BasketItem
    {
        /** @var Order $order */
        $order = $dest->getCollection()->getOrder();
        if ($order && $order->getField('STATUS_ID') != OrderStatusViewModel::STATUS_IN_PROCESS) {
            throw new LogicException('Can not add service if order is not in process');
        }

        $product = $product ?? ProductFactory::getById($dest->getProductId());
        if (!$product) {
            throw new InvalidArgumentException("It's not a diamond");
        }

        if (is_int($service)) {
            $service = PaidService::getById($service);
        }
        if (!($service instanceof PaidService) || !$service->canApply($product)) {
            throw new InvalidArgumentException('Service is not available');
        }

        $dest->setField('PRODUCT_XML_ID', $product->getProductTypeCode() . ':' . $product->getID());
        $props[$product->getProductTypeCode()] = $product->getID();

        $properties = [];
        foreach ($props as $code => $value) {
            $properties[] = [
                'NAME' => $code,
                'CODE' => $code,
                'VALUE' => $value,
            ];
        }

        static::removeServiceFromCart($service->getId(), 'owner:' . $dest->getId(), $order);

        /** @var Basket $basket - Корзина пользователя */
        $basket = $dest->getCollection()->getBasket();

        /** @var BasketItem $item */
        $item = $basket->createItem('catalog', $service->getId());
        $item->setFields([
            'QUANTITY' => $quantity,
            'CURRENCY' => CurrencyManager::getBaseCurrency(),
            'LID' => 's1',
            'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
            'PRICE_TYPE_ID' => $item->getField('PRICE_TYPE_ID'),
            'PRODUCT_XML_ID' => 'owner:' . $dest->getId(),
            'NAME' => $service['PROPERTY_NAME_RU_VALUE'],
        ]);

        $item->setFields([
            'PRICE' => $price,
            'CUSTOM_PRICE' => 'Y',
        ]);

        $item->getPropertyCollection()->setProperty($properties);

        if (!$basket->getOrder()) {
            $basket->save();
        } else {
            $item->save();
            $basket->save();
        }

        return $item;
    }

    /**
     * Получает массив идентификаторов товаров, находящихся в корзине
     *
     * @param CartTypeInterface|null $cartType
     * @return array
     */
    public static function getUserCartItems(CartTypeInterface $cartType = null): array
    {
        $type = $cartType ?? new DefaultCartType();
        $products = [];
        foreach (static::getUserCart($type)->getBasketItems() as $item) {
            $products[] = $item->getField('PRODUCT_ID');
        }

        return $products;
    }

    /**
     * Устанавливает новые значения пользовательских свойств корзины.
     *
     * @param BitrixBasketItem $basket
     * @param array $values
     * @throws Exception
     */
    public function applyBasketProperties(BitrixBasketItem $basket, array $values): void
    {
        $properties = BitrixBasketItemProperty::forBasket($basket->getId())
            ->keyBy('CODE')
            ->getList();

        db()->startTransaction();
        try {
            foreach ($values as $code => $value) {
                if ($property = $properties[$code]) {
                    if (empty($value)) {
                        $property->delete();
                    } else {
                        $property['VALUE'] = $value;
                        $property->save(['VALUE']);
                    }
                    continue;
                }

                if (empty($value)) {
                    continue;
                }

                $property = BitrixBasketItemProperty::create([
                    'BASKET_ID' => $basket->getId(),
                    'CODE' => $code,
                    'NAME' => $code,
                    'VALUE' => $value,
                ]);
                $properties[$code] = $property;
            }
            db()->commitTransaction();
        } catch (Exception $exception) {
            db()->rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * Получает цену корзины, приведенную к нужному виду
     *
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function getCartPriceTransformed(): string
    {
        return NumberHelper::transformNumberToPrice(
            $this->getCartPrice()
        );
    }

    /**
     * Получает цену корзины в текущей валюте
     *
     * @return float
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public function getCartPrice(): float
    {
        // Как было до 03.11.2020:
        /*
        return ceil(
            PriceHelper::getPriceInSpecificCurrency(
                $this->getCart()->getPrice(),
                Currency::getCurrentCurrency()
            )
        );
        */

        $currency = Currency::getCurrentCurrency();
        $currencyCode = $currency->getSymCode();
        $orderPrice = 0;
        /** @var BasketItemBase $basketItem */
        foreach ($this->getCart()->getBasketItems() as $basketItem) {
            $itemCurrencyCode = $basketItem->getCurrency();
            $itemPrice = $basketItem->getFinalPrice();
            if ($itemCurrencyCode !== $currencyCode) {
                $itemPrice = PriceHelper::getPriceInSpecificCurrency(
                    $itemPrice,
                    $currency
                );
            }
            $orderPrice += $itemPrice;
        }

        return ceil($orderPrice);
    }

    private function getProductId(BasketItem $item): int
    {
        $values = $item->getFieldValues();

        return (int)$values['PRODUCT_ID'];
    }

    /**
     * Проверяет, что элемент корзины является платной услугой
     *
     * @param BasketItem $item
     *
     * @return bool
     */
    private function isPaidService(BasketItem $item): bool
    {
        $productId = $this->getProductId($item);

        if (!$productId) {
            return false;
        }

        $paidServicesIds = array_map(
            'intval',
            PaidService::cache(TTL::HOUR)->getList()->pluck('ID')->toArray()
        );

        return in_array($productId, $paidServicesIds, true);
    }

    /**
     * Заполняет свойства заказа, которые будут отображаться в админке при покупке украшения из конструктора
     *
     * @param JewelryConstructorReadyProduct $product
     * @param                                $item
     */
    private static function applyConstructorBasketProperties(JewelryConstructorReadyProduct $product, $item): void
    {
        $basketProps = [];

        foreach ($product->getPropsForCart() as $key => $value) {
            $basketProps[] = [
                'NAME' => $key,
                'CODE' => $key,
                'VALUE' => $value,
            ];
        }

        $item->getPropertyCollection()->redefine($basketProps);
    }
}
