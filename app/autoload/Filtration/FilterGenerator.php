<?php

namespace App\Filtration;

use App\Filtration\Collection\FilterFieldCollection;
use App\Filtration\Collection\FilterItemCollection;
use App\Filtration\Exception\FilterItemInstanceException;
use App\Filtration\Factory\AbstractFilterFactory;
use App\Filtration\Factory\RegularFilterFactory;
use App\Filtration\Interfaces\FilterFieldInterface;
use App\Filtration\Interfaces\FilterRequestInterface;
use Throwable;

/**
 * Class FilterGenerator
 * Генератор фильтра или объекта запроса для заданного списка полей
 *
 * @package App\Filtration
 */
class FilterGenerator
{
    /** @var FilterFieldCollection Коллекция полей фильтра */
    protected $filterFieldCollection;

    /** @var FilterRequestInterface Запрос для генерации фильтра */
    protected $filterRequest;

    /** @var FilterItemCollection Базовые фильтры, независящие от запроса */
    protected $internalFilterItemCollection;

    /** @var AbstractFilterFactory Фабрика, генерируящая итоговый фильтр (или объект запроса) */
    protected $filterFactory;

    /**
     * @param FilterFieldCollection $filterFieldCollection Коллекция полей фильтра, по которым генерируется фильтр
     * @param FilterRequestInterface $filterRequest Запрос для генерации фильтра
     * @param FilterItemCollection|null $internalFilterItemCollection Внутренний фильтр, независящий от входящего запроса
     * @param AbstractFilterFactory|null $filterFactory Фабрика, генерируящая итоговый фильтр
     */
    public function __construct(
        FilterFieldCollection $filterFieldCollection,
        FilterRequestInterface $filterRequest,
        FilterItemCollection $internalFilterItemCollection = null,
        AbstractFilterFactory $filterFactory = null
    )
    {
        $this->filterFieldCollection = $filterFieldCollection;
        $this->filterRequest = $filterRequest;
        $this->internalFilterItemCollection = $internalFilterItemCollection ?? new FilterItemCollection();
        $this->filterFactory = $filterFactory ?? new RegularFilterFactory();
    }

    /**
     * Запрос для генерации фильтра
     *
     * @return FilterRequestInterface
     */
    public function getFilterRequest(): FilterRequestInterface
    {
        return $this->filterRequest;
    }

    /**
     * Возвращает коллекцию фильтруемых полей
     *
     * @return FilterFieldCollection|FilterFieldInterface[]
     */
    public function getFilterFields(): FilterFieldCollection
    {
        return $this->filterFieldCollection;
    }

    /**
     * Добавление фильтруемого поля в коллекцию
     *
     * @param FilterFieldInterface $filterField
     * @return static
     */
    public function addFilterField(FilterFieldInterface $filterField)
    {
        $this->getFilterFields()->offsetSet($filterField->getRequestFieldName(), $filterField);

        return $this;
    }

    /**
     * @return FilterItemCollection
     */
    protected function createResultFilterItemCollection(): FilterItemCollection
    {
        return (new FilterItemCollection())
            ->merge($this->internalFilterItemCollection);
    }

    /**
     * @return FilterItemCollection
     */
    protected function generateResultFilterItemCollection(): FilterItemCollection
    {
        $filterItemCollection = $this->createResultFilterItemCollection();

        $request = $this->getFilterRequest();
        foreach ($this->getFilterFields() as $filterField) {
            if ($filterField->isExcludedInFilter()) {
                continue;
            }
            if ($filterItem = $filterField->getFilterItem($request)) {
                $filterItemCollection->push($filterItem);
            }
        }

        return $filterItemCollection;
    }

    /**
     * Генерирует фильтр или объект запроса.
     * Тип возвращаемого результата определяется фабрикой.
     *
     * @return mixed
     * @throws FilterItemInstanceException
     * @throws Throwable
     */
    public function generate()
    {
        return $this->filterFactory->create(
            $this->generateResultFilterItemCollection()
        );
    }
}
