<?php

namespace App\Filtration\DataProvider\Embed;

use App\Filtration\Enum\ParamsEnum;
use Bitrix\Iblock\PropertyIndex\Facet;

/**
 * Class BitrixFacetDictionaryRelations
 *
 * @package App\Filtration\DataProvider\Embed
 */
class BitrixFacetDictionaryRelations
{
    /** @var int Лимит для выборки связанных данных за один запрос */
    protected const QUERY_CHUNK_SIZE = ParamsEnum::QUERY_CHUNK_SIZE;

    /** @var string Ключ хранилища связанных данных из внутреннего справочника индекса */
    protected const REL_DICTIONARY = 'DICTIONARY';

    /** @var array */
    protected $relatedData = [];

    /**
     * @return static
     */
    public function cleanRelatedData()
    {
        $this->relatedData = [];

        return $this;
    }

    /**
     * @param string $relType
     * @param int|string $relKey
     * @return static
     */
    protected function pushRelation(string $relType, $relKey)
    {
        if (!isset($this->relatedData[$relType]) || !array_key_exists($relKey, $this->relatedData[$relType])) {
            $this->relatedData[$relType][$relKey] = null;
        }

        return $this;
    }

    /**
     * @param string $relType
     * @return array|null
     */
    protected function getRelations(string $relType): ?array
    {
        return $this->relatedData[$relType] ?? null;
    }

    /**
     * @param string $relType
     * @param int|string $relKey
     * @return mixed|null
     */
    protected function getRelationValue(string $relType, $relKey)
    {
        return $this->relatedData[$relType][$relKey] ?? null;
    }

    /**
     * @param int $dictionaryValueId
     * @return static
     */
    public function pushDictionaryRelation(int $dictionaryValueId)
    {
        return $this->pushRelation(static::REL_DICTIONARY, $dictionaryValueId);
    }

    /**
     * @param int $dictionaryValueId
     * @return string|null
     */
    public function getDictionaryRelationValue(int $dictionaryValueId): ?string
    {
        $value = $this->getRelationValue(static::REL_DICTIONARY, $dictionaryValueId);

        return $value !== null ? (string)$value : null;
    }

    /**
     * @return array
     */
    public function getDictionaryRelations(): array
    {
        return $this->getRelations(static::REL_DICTIONARY) ?? [];
    }

    /**
     * @param Facet $facet
     * @return static
     */
    public function releaseRelations(Facet $facet)
    {
        //CTimeZone::Disable();
        $this->releaseDictionaryRelations($facet);
        //CTimeZone::Enable();

        return $this;
    }

    /**
     * @param Facet $facet
     * @return static
     */
    public function releaseDictionaryRelations(Facet $facet)
    {
        $relKey = static::REL_DICTIONARY;
        $idList = isset($this->relatedData[$relKey]) ? array_filter(array_keys($this->relatedData[$relKey])) : [];
        if (!$idList) {
            return $this;
        }

        foreach (array_chunk($idList, static::QUERY_CHUNK_SIZE) as $chunk) {
            foreach ($facet->getDictionary()->getStringByIds($chunk) as $dictionaryValueId => $value) {
                if ($value !== '') {
                    $this->relatedData[$relKey][$dictionaryValueId] = $value;
                }
            }
        }

        return $this;
    }
}
