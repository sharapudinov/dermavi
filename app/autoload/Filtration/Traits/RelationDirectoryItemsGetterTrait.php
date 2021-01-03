<?php

namespace App\Filtration\Traits;

use App\Filtration\Exception\ArgumentEmptyException;
use App\Filtration\Exception\LogicException;
use App\Filtration\Helper\HighloadblockHelper;
use App\Filtration\Helper\PropertiesHelper;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\SystemException;
use CIBlockProperty;
use Exception;

/**
 * Trait RelationDirectoryItemsGetterTrait
 *
 * @package App\Filtration\Traits
 */
trait RelationDirectoryItemsGetterTrait
{
    /** @var string|HighloadblockHelper */
    protected $highloadblockHelper = HighloadblockHelper::class;

    /** @var array */
    private $directoryItemsSort = [
        'UF_SORT' => 'ASC',
        'UF_NAME' => 'ASC',
        'ID' => 'ASC',
    ];

    /** @var array */
    private $directoryItemsBaseFilter = [];

    /** @var array */
    private $directoryItemsSelect = [
        'ID', 'UF_XML_ID', 'UF_NAME', 'UF_SORT',
    ];

    /** @var callable */
    private $directoryItemsTransformer;

    /**
     * @param int $propertyId
     * @param array $xmlIdList
     * @param int $queryChunkSize Ограничение элементов на один запрос
     * @return array
     * @throws ArgumentEmptyException
     * @throws ArgumentException
     * @throws LogicException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getDirectoryItems(int $propertyId, array $xmlIdList, int $queryChunkSize = 0): array
    {
        if (!$xmlIdList) {
            throw new ArgumentEmptyException('Argument xmlIdList is empty');
        }

        try {
            $propertyInfo = PropertiesHelper::getPropertyArray($propertyId);
            $hlbEntity = $this->highloadblockHelper::getHighloadBlockEntity(
                (string)($propertyInfo['USER_TYPE_SETTINGS']['TABLE_NAME'] ?? '')
            );
        } catch (Exception $exception) {
            $hlbEntity = null;
        }
        if (!$hlbEntity) {
            throw new LogicException('Unsupported property type');
        }

        $result = [];
        $params = $this->getDirectoryQueryBaseParams($hlbEntity);
        /** @todo Внимание! Разбиение запроса на куски делает сортировку на уровне БД бессмысленной */
        $chunks = $queryChunkSize > 0 ? array_chunk($xmlIdList, $queryChunkSize) : [$xmlIdList];
        foreach ($chunks as $chunk) {
            $params = $this->completeDirectoryQueryParams($params, $chunk);
            if (!$params) {
                continue;
            }
            /** @todo Кеширование */
            $iterator = $hlbEntity->getDataClass()::getList($params);
            while ($item = $iterator->fetch()) {
                $item = $this->transformDirectoryItem(
                    $this->transformDirectoryItemBase($item)
                );
                if (!$item) {
                    continue;
                }
                $result[$item['UF_XML_ID']] = $item;
            }
        }

        return $result;
    }

    /**
     * Возвращает базовые параметры для выборки, очищенные от несуществующих у сущности полей
     *
     * @param Entity $hlbEntity
     * @return array
     */
    protected function getDirectoryQueryBaseParams(Entity $hlbEntity): array
    {
        $params = [];
        if ($filter = $this->getDirectoryQueryBaseFilterParam($hlbEntity)) {
            $params['filter'] = $filter;
        }
        if ($select = $this->getDirectoryQueryBaseSelectParam($hlbEntity)) {
            $params['select'] = $select;
        }
        if ($order = $this->getDirectoryQueryBaseSortParam($hlbEntity)) {
            $params['order'] = $order;
        }
        //$params['cache'] = ['ttl' => '3600'];

        return $params;
    }

    /**
     * Убирает из параметра filter все несуществующие у сущности поля
     *
     * @param Entity $hlbEntity
     * @return array
     */
    protected function getDirectoryQueryBaseFilterParam(Entity $hlbEntity): array
    {
        // По умолчанию фильтров нет, а задаваемые фильтры вручную принимаем без проверок
        return $this->getDirectoryItemsBaseFilter();
    }

    /**
     * Убирает из параметра select все несуществующие у сущности поля
     *
     * @param Entity $hlbEntity
     * @return array
     */
    protected function getDirectoryQueryBaseSelectParam(Entity $hlbEntity): array
    {
        $select = $this->getDirectoryItemsSelect();
        if ($select) {
            $fieldsMap = $hlbEntity->getFields();
            foreach ($select as $key => $fieldName) {
                if (is_string($fieldName) && !isset($fieldsMap[$fieldName])) {
                    unset($select[$key]);
                }
            }
        }

        return $select;
    }

    /**
     * Убирает из параметра sort все несуществующие у сущности поля
     *
     * @param Entity $hlbEntity
     * @return array
     */
    protected function getDirectoryQueryBaseSortParam(Entity $hlbEntity): array
    {
        $order = $this->getDirectoryItemsSort();
        if ($order) {
            $fieldsMap = $hlbEntity->getFields();
            foreach ($order as $sortFieldName => $orderValue) {
                if (is_string($sortFieldName) && !isset($fieldsMap[$sortFieldName])) {
                    unset($order[$sortFieldName]);
                }
            }
        }

        return $order;
    }

    /**
     * @param array $params
     * @param array $xmlIdList
     * @return array
     */
    protected function completeDirectoryQueryParams(array $params, array $xmlIdList): array
    {
        $params['filter']['=UF_XML_ID'] = $xmlIdList;

        return $params;
    }

    /**
     * Добавление полей, которые возвращает нативный способ
     *
     * @param array $item
     * @return array
     */
    protected function transformDirectoryItemBase(array $item): array
    {
        if (!isset($item['UF_XML_ID'])) {
            return [];
        }
        $item['VALUE'] = (string)(array_key_exists('UF_NAME', $item) ? $item['UF_NAME'] : $item['UF_XML_ID']);
        $item['SORT'] = (int)(array_key_exists('UF_SORT', $item) ? $item['UF_SORT'] : $item['ID']);

        return $item;
    }

    /**
     * Метод получения элементов справочника нативным способом
     *
     * @param int $propertyId
     * @param array $xmlIdList
     * @param int $queryChunkSize Ограничение элементов на один запрос
     * @return array
     * @throws ArgumentEmptyException
     * @throws LogicException
     */
    public function getDirectoryItemsNative(int $propertyId, array $xmlIdList, int $queryChunkSize = 0): array
    {
        if (!$xmlIdList) {
            throw new ArgumentEmptyException('Argument xmlIdList is empty');
        }

        $propertyInfo = PropertiesHelper::getPropertyArray($propertyId);
        $userType = $propertyInfo ? CIBlockProperty::GetUserType($propertyInfo['USER_TYPE']) : [];
        if (!$userType || !isset($userType['GetExtendedValue'])) {
            throw new LogicException('Unsupported property type');
        }

        $preResult = [];
        /** @todo Внимание! Разбиение запроса на куски делает сортировку на уровне БД бессмысленной */
        $chunks = $queryChunkSize > 0 ? array_chunk($xmlIdList, $queryChunkSize) : [$xmlIdList];
        foreach ($chunks as $chunk) {
            $preResult[] = call_user_func(
                $userType['GetExtendedValue'],
                $propertyInfo,
                [
                    'VALUE' => $chunk,
                ]
            );
        }
        $preResult = array_merge(...$preResult);

        $result = [];
        foreach (($preResult ?? []) as $item) {
            $item = $this->transformDirectoryItem($item);
            if ($item) {
                $result[$item['UF_XML_ID']] = $item;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getDirectoryItemsSort(): array
    {
        return $this->directoryItemsSort;
    }

    /**
     * @param array $directoryItemsSort
     * @return static
     */
    public function setDirectoryItemsSort(array $directoryItemsSort)
    {
        $this->directoryItemsSort = $directoryItemsSort;

        return $this;
    }

    /**
     * @return array
     */
    public function getDirectoryItemsBaseFilter(): array
    {
        return $this->directoryItemsBaseFilter;
    }

    /**
     * @param array $directoryItemsBaseFilter
     * @return static
     */
    public function setDirectoryItemsBaseFilter(array $directoryItemsBaseFilter)
    {
        $this->directoryItemsBaseFilter = $directoryItemsBaseFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getDirectoryItemsSelect(): array
    {
        return $this->directoryItemsSelect;
    }

    /**
     * @param array $directoryItemsSelect
     * @return static
     */
    public function setDirectoryItemsSelect(array $directoryItemsSelect)
    {
        $this->directoryItemsSelect = $directoryItemsSelect;

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getDirectoryItemsTransformer(): ?callable
    {
        return $this->directoryItemsTransformer;
    }

    /**
     * Задает колбек для трансформации значения, полученного из выборки.
     * N.B. Если нужна возможность сохранения объекта в кеше, то сюда лучше не передавать анонимные функции
     *
     * @param callable $directoryItemsTransformer
     * @return static
     */
    public function setDirectoryItemsTransformer(callable $directoryItemsTransformer)
    {
        $this->directoryItemsTransformer = $directoryItemsTransformer;

        return $this;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function transformDirectoryItem(array $item): array
    {
        $transformer = $this->getDirectoryItemsTransformer();

        return $transformer ? (array)$transformer($item) : $item;
    }
}
