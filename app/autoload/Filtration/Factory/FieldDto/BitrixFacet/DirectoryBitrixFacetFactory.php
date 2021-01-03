<?php

namespace App\Filtration\Factory\FieldDto\BitrixFacet;

use App\Filtration\DataProvider\Embed\BitrixFacetFilterData;
use App\Filtration\Enum\ParamsEnum;
use App\Filtration\Exception\InvalidInstanceException;
use App\Filtration\Exception\LogicException;
use App\Filtration\Model\BitrixFacetIndexItem;
use App\Filtration\Relation\RelationValuesGetter;
use App\Filtration\Traits\FilterFieldDtoBuilder\Getter\DirectoryItemsGetterInnerTrait;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Class DirectoryBitrixFacetFactory
 * Генерация DTO по свойству типа "Справочник"
 *
 * @package App\Filtration\Factory\FieldDto\BitrixFacet
 */
class DirectoryBitrixFacetFactory extends AbstractBitrixFacetFactory
{
    use DirectoryItemsGetterInnerTrait;

    /** @var int Лимит выборки связанных данных за один запрос */
    protected $queryChunkSize = ParamsEnum::QUERY_CHUNK_SIZE;

    /**
     * @return RelationValuesGetter
     */
    protected function getDirectoryRelationValuesGetter(): RelationValuesGetter
    {
        return $this->getRelationValueGetter();
    }

    /**
     * @param array $params
     * @return static
     * @throws InvalidInstanceException
     * @throws LogicException
     */
    protected function validateParams(array $params)
    {
        parent::validateParams($params);

        if ((int)$params['propertyId'] <= 0) {
            throw new LogicException(
                'Property id is empty'
            );
        }

        return $this;
    }

    /**
     * @param array $params
     * @return array
     * @throws LogicException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    protected function generateVariantItems(array $params): array
    {
        /** @var BitrixFacetFilterData $facetData */
        $facetData = $params['facetData'];

        /** @var array $appliedFilterRequestValues */
        $appliedFilterRequestValues = $params['appliedFilterRequestValues'];

        $directoryValues = $this->getDirectoryItems(
            (int)$params['propertyId'],
            $this->filterFacetItemsValues(
                // Возвращает реальные значения (UF_XML_ID), которые вернула выборка из индекса для поля
                $facetData->getItemsDictionaryValues()
            ),
            $this->queryChunkSize
        );

        $itemsList = [];
        foreach ($directoryValues as $directoryItem) {
            $value = (string)($directoryItem['UF_XML_ID'] ?? '');
            $variantItem = [
                '*' => $directoryItem,
                'VALUE' => $value,
                'NAME' => $directoryItem['VALUE'] ?? '',
                'SORT' => $directoryItem['SORT'] ?? 0,
                'DESCRIPTION' => $directoryItem['FILTER_DESCRIPTION'] ?? '',
                'DOC_COUNT' => 0,
                'SELECTED' => $this->isValueSelected($value, $appliedFilterRequestValues),
            ];

            /** @var BitrixFacetIndexItem $facetItem */
            if ($value !== '' && $facetItem = $facetData->getFacetItemByDictValue($value)) {
                $variantItem['DOC_COUNT'] = $facetItem->getElementCount();
            }

            $itemsList[] = $variantItem;
        }

        return $itemsList;
    }
}
