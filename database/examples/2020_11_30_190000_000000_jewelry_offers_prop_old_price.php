<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

final class JewelryOffersPropOldPrice20201130190000000000 extends BitrixMigration
{
    private const IBLOCK_CODE = 'app_jewelry_sku';

    private const IBLOCK_TYPE = 'catalog';

    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $this->createProps();
    }

    /**
     * Reverse the migration.
     *
     * @throws LoaderException
     */
    public function down()
    {
        $this->deleteProps();
    }

    /**
     * @return array
     */
    private function getNewPropsList(): array
    {
        $list = [];
        $list[] = [
            'PROPERTY_TYPE' => 'N',
            'ACTIVE' => 'Y',
            'CODE' => 'OLD_PRICE',
            'NAME' => 'Старая цена готового изделия',
            'HINT' => 'Если значение задано, то предложение будет выводиться в разделе "Распродажа"',
            'MULTIPLE' => 'N',
            'SORT' => '110',
            'ROW_COUNT' => '1',
            'COL_COUNT' => '20',
            'MULTIPLE_CNT' => '1',
            'FILTRABLE' => 'Y',
        ];

        return $list;
    }

    /**
     * @return array
     */
    private function getUpdatePropsList(): array
    {
        $list = [];
        $list[] = [
            'CODE' => 'PRICE',
            'SORT' => '100',
        ];
        $list[] = [
            'CODE' => 'FINAL_PRICE',
            'SORT' => '120',
        ];

        return $list;
    }

    /**
     * @return int
     * @throws LoaderException
     */
    private function getIBlockId(): int
    {
        Loader::includeModule('iblock');

        $iblockId = 0;
        try {
            $iblockId = $this->getIblockIdByCode(static::IBLOCK_CODE, static::IBLOCK_TYPE);
        } catch (Exception $exception) {
            // it's ok
        }

        return $iblockId;
    }

    /**
     * @throws LoaderException
     */
    private function createProps(): void
    {
        $iblockId = $this->getIBlockId();
        if (!$iblockId) {
            return;
        }
        foreach ($this->getNewPropsList() as $fields) {
            $item = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fields['CODE']])->Fetch();
            if ($item) {
                continue;
            }
            $fields['IBLOCK_ID'] = $iblockId;
            (new CIBlockProperty())->Add($fields);
        }

        foreach ($this->getUpdatePropsList() as $fields) {
            $item = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fields['CODE']])->Fetch();
            if ($item) {
                (new CIBlockProperty())->Update($item['ID'], $fields);
            }
        }
    }

    /**
     * @throws LoaderException
     */
    private function deleteProps(): void
    {
        $iblockId = $this->getIBlockId();
        if (!$iblockId) {
            return;
        }

        if (!$iblockId) {
            return;
        }
        foreach ($this->getNewPropsList() as $fields) {
            $item = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fields['CODE']])->Fetch();
            if ($item) {
                CIBlockProperty::Delete($item['ID']);
            }
        }
    }
}
