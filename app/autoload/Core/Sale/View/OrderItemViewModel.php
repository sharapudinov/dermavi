<?php
namespace App\Core\Sale\View;

use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Auxiliary\Sale\BitrixBasketItemProperty;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\ProductInterface;
use Illuminate\Support\Collection;
use LogicException;

/**
 * Класс для модели представления позиции заказа.
 * Class OrderItemViewModel
 * @package App\Core\Sale\View
 *
 * @property OrderItemViewModel|null $engraving
 * @property OrderItemViewModel|null $certificate
 */
class OrderItemViewModel
{
    /** @var int Идентификатор корзины */
    private $basketId;

    /** @var int Идентификатор заказа */
    private $orderId;

    /** @var int Идентификатор родительской позиции */
    private $ownerId = 0;

    /** @var Catalog|PaidService|JewelrySku */
    private $product;

    /** @var float Цена */
    private $price;

    /** @var CurrencyEntity Валюта */
    private $currency;

    /** @var int Количество */
    private $quantity;

    /** @var string Ссылка на виджет бриллианта */
    private $widgetLink;

    /** @var array Пользовательские свойства позиции заказа */
    private $customProperties = [];

    /** @var Collection|OrderItemViewModel[] Дополнительные услуги */
    private $attachedServices;

    /**
     * OrderItemViewModel constructor.
     * @param BitrixBasketItem $source
     * @param Catalog|null $product
     */
    public function __construct(BitrixBasketItem $source)
    {
        $this->basketId = $source->getId();
        $this->orderId = $source->getOrderId();
        $this->price = $source->getPrice() * $source->getQuantity();
        $this->quantity = $source->getQuantity();
        $this->currency = (new Currency())->getCurrencyByAlphabetCode($source->getCurrency());

        $this->product = $source->getProduct() ?? $source->service;

        if (preg_match('/^owner:(\d+)$/', $source->getExternalId(), $matches)) {
            $this->ownerId = (int)$matches[1];
        }

        if ($this->product) {
            $this->attachedServices = collect();
        }

        if ($source->properties) {
            $this->customProperties = $source->properties
                ->mapWithKeys(function (BitrixBasketItemProperty $property) {
                    return [$property->getCode() => $property->getValue()];
                })
                ->all();
        }
    }

    /**
     * Возвращает идентификатор корзины.
     * @return int
     */
    public function getBasketId(): int
    {
        return $this->basketId;
    }

    /**
     * Возвращает идентификатор заказа.
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * Возвращает идентификатор родительского позиции.
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * Возвращает цену позиции в заданной валюте.
     *
     * @param CurrencyEntity $currency
     * @return float
     */
    public function getPrice(): float
    {
        $currentCurrency = Currency::getCurrentCurrency();

        //fixme костыль, на случай когда товар отправляется в usd
        if ((Currency::USD_CURRENCY === $currentCurrency->getSymCode()) && (user() && user()->isAuthorized()))
        {
            return ceil($this->price);
        }

        return ceil(PriceHelper::getPriceInSpecificCurrency($this->price, $currentCurrency));
    }

    /**
     * Возвращает количество товара.
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * Возвращает истину, если данная позиция заказа является бриллиантом.
     * @return bool
     */
    public function isDiamond(): bool
    {
        return $this->product instanceof Diamond && !($this->product->auctionLot || $this->product->auctionPBLot);
    }

    /**
     * Возвращает истину, если данная позиция заказа является аукционным бриллиантом
     * @return bool
     */
    public function isAuctionDiamond(): bool
    {
        return $this->product instanceof Diamond && ($this->product->auctionLot || $this->product->auctionPBLot);
    }

    /**
     * Возвращает истину, если данная позиция заказа является услугой.
     * @return bool
     */
    public function isService(): bool
    {
        return $this->product instanceof PaidService;
    }

    /**
     * Возвращает истину, если данная позиция заказа является ЮИ.
     * @return bool
     */
    public function isJewelry(): bool
    {
        return $this->product instanceof JewelrySku;
    }

    /**
     * Возвращает истину, если позиция является готовым изделием в конструкторе
     *
     * @return bool
     */
    public function isConstructorReadyProduct(): bool
    {
        return $this->product instanceof JewelryConstructorReadyProduct;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->getDiamond() ?? $this->getJewelrySku() ?? $this->getCombination() ?? null;
    }

    /**
     * Возвращает стоимость дополнительных услуг
     * @return int
     */
    public function getAttachedServicePrice(): int
    {
        $price=0;
        if($this->hasAttachedServices()) {
            foreach ($this->attachedServices as $service) {
                $price += $service->getPrice();
            }
        }
        return $price;
    }

    /**
     * Возвращает кол-во дней для доставки
     *
     * @param string $code - Код кастомного свойства
     *
     * @return int
     */
    public function getAttachedServiceDeliveryDays(string $code): int
    {
        $days=0;
        if($this->hasAttachedServices()) {
            foreach ($this->attachedServices as $service) {
                $days += $service->getCustomProperty($code);
            }
        }
        return $days;
    }

    /**
     * Возвращает дополнительную услугу, присоединенную к товару.
     * @param string $category
     * @return OrderItemViewModel|null
     */
    public function getAttachedService(string $category): ?OrderItemViewModel
    {
        return $this->attachedServices !== null ? $this->attachedServices[$category] : null;
    }
    /**
     * Возвращает доп услуги товара
     * @param string $category
     * @return OrderItemViewModel[]|Collection
     */
    public function getAttachedServices(): Collection
    {
        return $this->attachedServices;
    }

    /**
     * Проверяет есть ли у товара дополнительные платные услуги.
     * @return bool
     */
    public function hasAttachedServices(): bool
    {
        return $this->attachedServices !== null && $this->attachedServices->isNotEmpty();
    }

    /**
     * Возвращает ссылку на виджет бриллианта.
     * @return string
     */
    public function getWidgetLink(): string
    {
        if ($this->widgetLink !== null) {
            return $this->widgetLink;
        }

        $diamond = $this->getDiamond();
        if ($diamond === null) {
            return '';
        }

        return $this->widgetLink = (string) CutwiseWidget::getAvailableWidgetLink($diamond->getPacketNumber(), 1440);
    }

    /**
     * Возвращает отформатированное представление цены.
     * @return string
     */
    public function formatPrice(): string
    {
        return NumberHelper::transformNumberToPrice($this->getPrice());
    }

    /**
     * Возвращает значение пользовательского свойства по коду.
     * @param string $code
     * @return string
     */
    public function getCustomProperty(string $code): string
    {
        return (string) $this->customProperties[$code];
    }

    /**
     * Получает итоговую цену на позицию (включая платные услуги)
     *
     * @return float
     */
    public function getTotalPriceForPosition(): float
    {
        $engraving = $this->getAttachedService('engraving');
        $certificate = $this->getAttachedService('certificate');

        return $this->getPrice()
            + ($engraving ? $engraving->getPrice() : 0)
            + ($certificate ? $certificate->getPrice() : 0);
    }

    /**
     * Добавляет платную услугу к бриллианту.
     * @param OrderItemViewModel $viewModel
     * @return static
     */
    public function attachService(OrderItemViewModel $viewModel): self
    {
        if ((!$this->isDiamond() && !$this->isJewelry() && !$this->isConstructorReadyProduct()) || !$viewModel->isService()) {
            throw new LogicException("Cannot add paid service");
        }

        $this->attachedServices[$viewModel->getService()->getCategoryXmlId()] = $viewModel;
        return $this;
    }

    /**
     * Возвращает заказанную дополнительную услугу по ее псевдониму.
     * @param string $name
     * @return OrderItemViewModel|null
     */
    public function __get($name)
    {
        if ($this->hasAttachedServices()) {
            if ($code = PaidServiceCategory::aliasToCode($name)) {
                return $this->getAttachedService($code);
            }
        }
        return null;
    }
}
