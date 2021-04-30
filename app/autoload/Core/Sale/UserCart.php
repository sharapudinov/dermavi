<?php

namespace App\Core\Sale;

use App\Core\Currency\Currency;
use App\Core\Sale\Entity\CartType\AuctionsCartType;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Traits\Sale\CartTrait;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Helpers\UserHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixBasketItemProperty;
use App\Models\Catalog\ProductInterface;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\BasketItemBase;
use Bitrix\Sale\Fuser;
use CSaleUser;
use Exception;

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

    /** @var bool $isCartChanged - Флаг, указывающий на то, что корзина изменялась */
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
    private function __clone()
    {
    }

    /**
     * Prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup()
    {
    }

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

        $this->loadBasketItems($basketItemsIds);

        $this->cart = $cart;
    }

    /**
     * Удаляет из корзины платные услуги, для которых в корзине не осталось товаров
     *
     * @param Basket $cart
     *
     * @return bool
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     *
     * @throws \Bitrix\Main\ArgumentNullException
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
                    logger()->error('Ошибка при удалении товаров из корзины' . implode("\n", $exception->getMessage()));
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
        $productId,
        $quantity,
        $properties,
        CartTypeInterface $cartType

    ): int {
        $cart = static::getUserCart($cartType);
        $price = $price ?? PriceHelper::getFinalPriceInCurrency($productId)['FINAL_PRICE'];
        $item = $cart->getExistsItem('catalog', $productId,(array) $properties);
        if (!$item) {
            $item = $cart->createItem('catalog', $productId);
        }
        $item->setFields(
            [
                'QUANTITY'               => $quantity,
                'CURRENCY'               => $cartType->getCurrency()->getSymCode(),
                'LID'                    => $cartType->getSiteId(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                'PRICE'                  => $price,
            ]
        );


        $result = $cart->save();
        if (!$result->isSuccess()) {
            logger()->error('Ошибка при добавлении товара в корзину' . implode("\n", $result->getErrorMessages()));
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
        $basketItem = $cart->getBasketItems()->first(
            function (BitrixBasketItem $item) use ($productId) {
                return $item->getProductId() === $productId;
            }
        );

        /** @var BasketItem[] $items */
        $items = $bitrixCart->getBasketItems();
        foreach ($items as $item) {
            if (!$productId || ($productId === (int)$item->getProductId())) {
                // Удаляем продукт из корзины
                $item->delete();
            }
        }

        $bitrixCart->save();
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

                $property = BitrixBasketItemProperty::create(
                    [
                        'BASKET_ID' => $basket->getId(),
                        'CODE'      => $code,
                        'NAME'      => $code,
                        'VALUE'     => $value,
                    ]
                );
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
}
