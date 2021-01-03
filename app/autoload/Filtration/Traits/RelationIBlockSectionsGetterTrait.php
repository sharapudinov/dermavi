<?php

namespace App\Filtration\Traits;

use App\Filtration\Exception\ArgumentEmptyException;
use CIBlockSection;

/**
 * Trait RelationIBlockSectionsGetterTrait
 *
 * @package App\Filtration\Traits
 */
trait RelationIBlockSectionsGetterTrait
{
    /** @var array */
    private $sectionsListSort = [];

    /** @var array */
    private $sectionsListBaseFilter = [
        'GLOBAL_ACTIVE' => 'Y',
    ];

    /** @var array */
    private $sectionsListSelect = [
        'ID', 'IBLOCK_ID', 'NAME', 'CODE', 'XML_ID', 'SORT',
        'LEFT_MARGIN', 'DEPTH_LEVEL',
    ];

    /** @var callable */
    private $sectionsListItemTransformer;

    /**
     * @param array $sectionsIdList
     * @param int $iblockId
     * @param int $queryChunkSize Ограничение элементов на один запрос
     * @return array
     * @throws ArgumentEmptyException
     */
    public function getSectionsList(array $sectionsIdList, int $iblockId = 0, int $queryChunkSize = 0): array
    {
        if (!$sectionsIdList) {
            throw new ArgumentEmptyException('Argument sectionsIdList is empty');
        }

        $result = [];
        $chunks = $queryChunkSize > 0 ? array_chunk($sectionsIdList, $queryChunkSize) : [$sectionsIdList];
        foreach ($chunks as $chunk) {
            $filter = $this->getSectionsListBaseFilter();
            $filter['ID'] = $chunk;
            if ($iblockId > 0) {
                $filter['IBLOCK_ID'] = $iblockId;
            }

            /** @todo Кеширование */
            $iterator = CIBlockSection::GetList(
                $this->getSectionsListSort(),
                $filter,
                false,
                $this->getSectionsListSelect()
            );
            while ($item = $iterator->Fetch()) {
                $item['DEPTH_NAME'] = str_repeat('.', $item['DEPTH_LEVEL']) . $item['NAME'];
                $item = $this->transformSectionsListItem($item);
                if ($item) {
                    $result[$item['ID']] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getSectionsListSort(): array
    {
        return $this->sectionsListSort;
    }

    /**
     * @param array $sectionsListSort
     * @return static
     */
    public function setSectionsListSort(array $sectionsListSort)
    {
        $this->sectionsListSort = $sectionsListSort;

        return $this;
    }

    /**
     * @return array
     */
    public function getSectionsListBaseFilter(): array
    {
        return $this->sectionsListBaseFilter;
    }

    /**
     * @param array $sectionsListBaseFilter
     * @return static
     */
    public function setSectionsListBaseFilter(array $sectionsListBaseFilter)
    {
        $this->sectionsListBaseFilter = $sectionsListBaseFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getSectionsListSelect(): array
    {
        return $this->sectionsListSelect;
    }

    /**
     * @param array $sectionsListSelect
     * @return static
     */
    public function setSectionsListSelect(array $sectionsListSelect)
    {
        $this->sectionsListSelect = $sectionsListSelect;

        return $this;
    }

    /**
     * @return callable|null
     */
    protected function getSectionsListItemTransformer(): ?callable
    {
        return $this->sectionsListItemTransformer;
    }

    /**
     * Задает колбек для трансформации значения, полученного из выборки.
     * N.B. Если нужна возможность сохранения объекта в кеше, то сюда лучше не передавать анонимные функции
     *
     * @param callable $sectionsListItemTransformer
     * @return static
     */
    public function setSectionsListItemTransformer(callable $sectionsListItemTransformer)
    {
        $this->sectionsListItemTransformer = $sectionsListItemTransformer;

        return $this;
    }

    /**
     * @param array $item
     * @return array
     */
    protected function transformSectionsListItem(array $item): array
    {
        $transformer = $this->getSectionsListItemTransformer();

        return $transformer ? (array)$transformer($item) : $item;
    }
}
