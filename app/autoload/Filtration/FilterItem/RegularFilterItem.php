<?php

namespace App\Filtration\FilterItem;

use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterItemRegularInterface;

/**
 * Class RegularFilterItem
 * Элемент обычного фильтра в виде массива
 *
 * @package App\Filtration\FilterItem
 */
class RegularFilterItem extends AbstractFilterItem implements FilterItemRegularInterface
{
    /** @var array|null */
    protected $value;

    /**
     * @param array|null $value
     * @param FilterFieldInterface|null $filterField
     */
    public function __construct(array $value = null, FilterFieldInterface $filterField = null)
    {
        parent::__construct($filterField);
        if ($value !== null) {
            $this->setValue($value);
        }
    }

    /**
     * Возвращает фильтр по полю
     * Пример: ['=PROPERTY_FOO' => 'BAR']
     *
     * @param null $params
     * @return array|null
     */
    public function getValue($params = null): ?array
    {
        return $this->value;
    }

    /**
     * @param array $value
     * @return static
     */
    public function setValue(array $value)
    {
        $this->value = $value;

        return $this;
    }
}
