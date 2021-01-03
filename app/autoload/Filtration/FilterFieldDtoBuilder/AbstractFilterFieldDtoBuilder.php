<?php

namespace App\Filtration\FilterFieldDtoBuilder;

use App\Filtration\Factory\FieldDto\AbstractFieldDtoFactory;
use App\Filtration\Interfaces\FilterFieldDtoBuilderInterface;
use App\Filtration\Model\FilterFieldInfo;
use App\Filtration\Traits;

/**
 * Class AbstractFilterFieldDtoBuilder
 * Генерация DTO полей фильтра
 *
 * @package App\Filtration\FilterFieldDtoBuilder
 */
abstract class AbstractFilterFieldDtoBuilder implements FilterFieldDtoBuilderInterface
{
    use Traits\FilterFieldDtoBuilder\WithFilterFieldTrait,
        Traits\FilterFieldDtoBuilder\WithFilterFieldInfoTrait,
        Traits\FilterFieldDtoBuilder\WithAppliedFilterRequestValuesTrait,
        Traits\FilterFieldDtoBuilder\WithFilterFieldDtoCodeTrait;

    /** @var AbstractFieldDtoFactory */
    protected $fieldDtoFactory;

    /**
     * Возвращает ID сущности индекса: ID свойства или цены
     *
     * @return int
     */
    abstract public function getFilterFieldEntityPrimaryKey(): int;

    /**
     * @return FilterFieldInfo
     */
    abstract protected function buildFilterFieldInfo(): FilterFieldInfo;

    /**
     * @param AbstractFieldDtoFactory $fieldDtoFactory
     * @return static
     */
    public function setFieldDtoFactory(AbstractFieldDtoFactory $fieldDtoFactory)
    {
        $this->fieldDtoFactory = $fieldDtoFactory;

        return $this;
    }

    /**
     * @return AbstractFieldDtoFactory
     */
    public function getFieldDtoFactory(): AbstractFieldDtoFactory
    {
        if ($this->fieldDtoFactory === null) {
            $this->fieldDtoFactory = $this->buildFieldDtoFactory();
        }

        return $this->fieldDtoFactory;
    }

    /**
     * @return AbstractFieldDtoFactory
     */
    abstract protected function buildFieldDtoFactory(): AbstractFieldDtoFactory;
}
