<?php

namespace App\Core\Product\FilterField;

use App\Filtration\Enum\RequestEnum;
use App\Helpers\PriceHelper;
use Bitrix\Main\ObjectNotFoundException;

/**
 * Class AbstractNumberPriceFilter
 * Фильтр диапазонного поля цен, хранящихся в number-свойствах, для использования в фильтрах в виде массива
 *
 * @package App\Core\Diamond\FilterField
 */
abstract class AbstractNumberPriceFilter extends AbstractRangeFilter
{
    /** @var string Символьный код валюты, в которой хранится значение */
    protected $valueCurrencyCode = 'USD';

    /** @var bool Добавлять ли налоги (НДС) к ценам при пересчете */
    protected $recalculateWithTax = true;

    /**
     * Выбранная валюта
     *
     * @return string
     */
    protected function getCurrencySelected(): string
    {
        return $this->getUserContext()->getCurrency();
    }

    /**
     * @return bool
     */
    protected function isTaxesConsideredSelected(): bool
    {
        return PriceHelper::isTaxesConsideredByContext($this->getUserContext());
    }

    /**
     * Конвертирует цену из валюты, в которой она хранится, в валюту контекста
     *
     * @param float $price
     * @return float
     * @throws ObjectNotFoundException
     */
    public function convertPriceCurrency(float $price): float
    {
        return PriceHelper::convertPriceCurrency(
            $price,
            $this->valueCurrencyCode,
            $this->getCurrencySelected()
        );
    }

    /**
     * Конвертирует цену из валюты контекста в валюту хранения
     *
     * @param float $price
     * @return float
     * @throws ObjectNotFoundException
     */
    public function convertBackPriceCurrency(float $price): float
    {
        return PriceHelper::convertPriceCurrency(
            $price,
            $this->getCurrencySelected(),
            $this->valueCurrencyCode
        );
    }

    /**
     * Добавляет к цене налоги (НДС), если в текущем контексте они предусмотрены
     *
     * @param float $price
     * @return float
     */
    public function calculatePriceWithTax(float $price): float
    {
        if ($this->isTaxesConsideredSelected()) {
            $price = PriceHelper::calculateWithTax($price);
        }

        return $price;
    }

    /**
     * Убирает из цены налоги (НДС), если в текущем контексте они предусмотрены
     *
     * @param float $price
     * @return float
     */
    public function calculatePriceWithoutTax(float $price): float
    {
        if ($this->isTaxesConsideredSelected()) {
            $price = PriceHelper::calculateWithoutTax($price);
        }

        return $price;
    }

    /**
     * Перерасчет цены: к исходной цене добавляет налоги и конвертирует в валюту согласно текущему контексту
     *
     * @param float $price
     * @return float
     * @throws ObjectNotFoundException
     */
    public function recalculatePrice(float $price): float
    {
        return $this->convertPriceCurrency(
            $this->recalculateWithTax ? $this->calculatePriceWithTax($price) : $price
        );
    }

    /**
     * Обратный перерасчет: из цены в текущем контексте вычитает налоги и конвертирует в валюту хранения
     *
     * @param float $price
     * @return float
     * @throws ObjectNotFoundException
     */
    public function recalculateBackPrice(float $price): float
    {
        return $this->convertBackPriceCurrency(
            $this->recalculateWithTax ? $this->calculatePriceWithoutTax($price) : $price
        );
    }

    /**
     * Конвертация валюты из запроса в валюту цены.
     * Также вычитаются налоги, если в текущем контексте они предусмотрены.
     *
     * @param float|null $from
     * @param float|null $to
     * @throws ObjectNotFoundException
     */
    protected function formatFilterRangeValue(&$from = null, &$to = null): void
    {
        parent::formatFilterRangeValue($from, $to);

        // Добавление и вычитание 1 было в исходной реализации, см. FilterFieldsBase::formatPrice().
        // Вернуть, если в этом был смысл.
        if ($from !== null) {
            //$from = (float)$from - 1;
            $from = $this->recalculateBackPrice((float)$from);
        }
        if ($to !== null) {
            //$to = (float)$to + 1;
            $to = $this->recalculateBackPrice((float)$to);
        }
    }

    /**
     * Подготовка цен из запроса (конвератция валюты, работа с налогами)
     * для применения их в фильтре фасетного индекса
     *
     * @param mixed $filterValues
     * @return mixed
     * @throws ObjectNotFoundException
     * @internal
     */
    public function formatBitrixFacetFilterValues($filterValues)
    {
        if ($filterValues && is_array($filterValues)) {
            $from = $filterValues[RequestEnum::RANGE_FIELD_FROM] ?? null;
            $to = $filterValues[RequestEnum::RANGE_FIELD_TO] ?? null;
            $this->formatFilterRangeValue($from, $to);
            if ($from !== null) {
                $filterValues[RequestEnum::RANGE_FIELD_FROM] = $from;
            }
            if ($to !== null) {
                $filterValues[RequestEnum::RANGE_FIELD_TO] = $to;
            }
        }

        return $filterValues;
    }
}
