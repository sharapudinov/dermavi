<?php

namespace App\Core\Jewelry\Constructor\BlanksAndDiamonds;

use App\Helpers\PriceHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс для рассчёта цены комплекта.
 */
class BlanksAndDiamondsPrice
{
    /** @var JewelryBlank */
    private JewelryBlank $blank;

    /** @var Diamond[]|Collection */
    private Collection $combination;

    /**
     * BlanksAndDiamondsPrice constructor.
     *
     * @param JewelryBlank         $blank       Модель заготовки
     * @param Collection|Diamond[] $combination Коллекция бриллиантов, доступных для текущей заготовки
     */
    public function __construct(JewelryBlank $blank, Collection $combination)
    {
        $this->blank = $blank;
        $this->combination = $combination;
    }

    /**
     * Возвращает цену комбинации.
     *
     * @return int
     */
    public function getPrice(): int
    {
        return $this->getBlankPrice() + $this->getCombinationPrice();
    }

    /**
     * Возвращает цену оправы.
     *
     * @return int
     */
    private function getBlankPrice(): int
    {
        return (int)$this->blank->getNonTransformedPrice();
    }

    /**
     * Возвращает цену бриллиантов.
     *
     * @return int
     */
    private function getCombinationPrice(): int
    {
        return (int)$this->combination->map(static function (Diamond $diamond) {
            return (int)ceil(PriceHelper::getPriceInCurrentCurrency(
                PriceHelper::calculateWithTax(
                    (float)$diamond['PROPERTY_PRICE_VALUE']
                ),
                null,
                'RUB'
            ));
        })->sum();
    }
}
