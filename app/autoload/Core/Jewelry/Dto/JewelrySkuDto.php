<?php

namespace App\Core\Jewelry\Dto;

use App\Core\Currency\Currency;
use App\Core\LazyLoadDto;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Models\Jewelry\JewelrySku;
use Illuminate\Database\Eloquent\Collection;

/**
 * Модель представления ЮИ.
 * Class JewelryViewModel
 *
 * @package App\Core\Sale\History
 * @property string              $metal
 * @property string              $fineness
 * @property string              $color
 * @property string              $material          Комбинация цвет + металл + проба
 * @property int                 $diamondsCount
 * @property float               $weight            Общий вес
 * @property float               $diamondsWeight    Вес вставок
 * @property string              $number            Артикул
 * @property string              $externalId        Бирка
 * @property float               $price             Цена в текущей валюте
 * @property float               $oldPrice          Старая цена в текущей валюте
 * @property float               $basePrice         Базовая цена (без конвертации валют, в рублях)
 * @property float               $baseOldPrice      Базовая старая цена (без конвертации валют, в рублях)
 * @property string              $formattedPrice
 * @property array               $photos
 * @property array               $photosPreview
 * @property int                 $id
 * @property string              idForFeed
 * @property string              $name
 * @property string              $detailUrl
 * @property float               $size
 * @property bool                $inBasket
 * @property bool                $isAvailable
 * @property string              $video             Ссылка на директорию с фотографиями для 360
 * @property Collection|JewelryDiamondDto[] $diamonds
 */
class JewelrySkuDto extends LazyLoadDto
{
    public function __construct(JewelrySku $sku)
    {
        // Тут данные, которые можно кешировать, остальные через LazyLoad
        $attributes = [
            'id'             => $sku->getId(),
            'idForFeed'      => $sku->getIdForFeed(),
            'name'           => $sku->getName(),
            'size'           => $sku->getSize(),
            'metal'          => $sku->metal ? $sku->metal->getName() : '',
            'fineness'       => $sku->fineness ? $sku->fineness->getName() : '',
            'color'          => $sku->metalColor ? $sku->metalColor->getName() : '',
            'material'       => $sku->getMaterial(),
            'diamondsCount'  => $sku->diamonds->mapInto(JewelryDiamondDto::class)->sum('count'),
            'weight'         => $sku->getFullWeight(),
            'basePrice'      => $sku->getFinalPrice(),
            'baseOldPrice'   => $sku->getOldPrice(),
            'photos'         => $sku->getPhotoSmall(700, 700),
            'photosPreview'  => $sku->getPhotoSmall(),
            'diamondsWeight' => $sku->getDiamondsWeight(),
            'number'         => $sku->getCode(),
            'externalId'     => $sku->getExternalId(),
            'diamonds'       => $sku->diamonds->mapInto(JewelryDiamondDto::class),
            'detailUrl'      => $sku->getDetailPageUrl(),
            'isAvailable'    => $sku->isAvailableForSelling(),
            'video'          => $sku->getVideo() ?? '',
            'types'          => $sku->getDiamondTypes() ?? [],
            'colorsGia'      => $sku->getDiamondColorsGia() ?? [],
            'shapes'         => $sku->getDiamondShapes() ?? [],
            'claritiesGia'   => $sku->getDiamondClaritiesGia() ?? [],
        ];

        parent::__construct($attributes);
    }

    /**
     * Находится ли товар в корзине
     *
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function getInBasket(): bool
    {
        return UserCart::getInstance(new DefaultCartType())->getCart()->getExistsItem('catalog', $this->id) ? true : false;
    }

    /**
     * @param $price
     *
     * @return string
     */
    protected function getFormattedPrice()
    {
        return NumberHelper::transformNumberToPrice($this->price) . ' ' . Currency::getCurrentCurrency()->getSymbol();
    }

    /**
     * @return string
     */
    protected function getFormattedOldPrice(): string
    {
        return NumberHelper::transformNumberToPrice($this->oldPrice) . ' ' . Currency::getCurrentCurrency()->getSymbol();
    }

    /**
     * @return int
     */
    protected function getDiscountPercentValue(): int
    {
        if ($this->baseOldPrice <= 0 || $this->basePrice <= 0 || $this->baseOldPrice <= $this->basePrice) {
            return 0;
        }

        return round((1 - ($this->basePrice / $this->baseOldPrice)) * 100);
    }

    /**
     * @param $price
     *
     * @return string
     */
    protected function getPrice()
    {
        return PriceHelper::getPriceInSpecificCurrency(
            $this->basePrice,
            Currency::getCurrentCurrency()
        );
    }

    /**
     * @return float
     */
    protected function getOldPrice(): float
    {
        return PriceHelper::getPriceInSpecificCurrency(
            $this->baseOldPrice,
            Currency::getCurrentCurrency()
        );
    }

    /**
     * Возвращает формы бриллиантов через запятую
     * @return string
     */
    public function getDiamondsShapes(): string
    {
        $shapes = $this->diamonds->map(function (JewelryDiamondDto $diamond) {
            return $diamond->shape;
        });
        return implode(',', $shapes->values()->all());
    }

    /**
     * Возвращает цвета бриллиантов через запятую
     * @return string
     */
    public function getDiamondsColors(): string
    {
        $colors = $this->diamonds->map(function (JewelryDiamondDto $diamond) {
            return $diamond->color;
        });

        return implode(',', $colors->values()->all());
    }
}
