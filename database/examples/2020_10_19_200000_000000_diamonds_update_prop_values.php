<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

final class DiamondsUpdatePropValues20201019200000000000 extends BitrixMigration
{
    private const IBLOCK_CODE = 'diamond';

    private const IBLOCK_TYPE = 'catalog';

    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $this->updateElements();
    }

    /**
     * Reverse the migration.
     */
    public function down()
    {
        // Оставляем элемент
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    private function updateElements(): void
    {
        Loader::includeModule('iblock');

        $iblockId = 0;
        try {
            $iblockId = $this->getIblockIdByCode(static::IBLOCK_CODE, static::IBLOCK_TYPE);
        } catch (Exception $exception) {
            // it's ok
        }

        if (!$iblockId) {
            return;
        }

        $iterator = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => $iblockId,
                '!SECTION_CODE' => 'FOR_AUCTIONS',
            ],
            false,
            false,
            [
                'ID', 'IBLOCK_ID',
                'PROPERTY_SYMMETRY',
                'PROPERTY_POLISH',
                'PROPERTY_CUT',
            ]
        );
        while ($item = $iterator->Fetch()) {
            $updateProps = [];
            if (($item['PROPERTY_SYMMETRY_VALUE'] ?? '') !== '' && (int)$item['PROPERTY_SYMMETRY_VALUE'] <= 0) {
                $updateProps['SYMMETRY'] = '';
            }
            if (($item['PROPERTY_POLISH_VALUE'] ?? '') !== '' && (int)$item['PROPERTY_POLISH_VALUE'] <= 0) {
                $updateProps['POLISH'] = '';
            }
            if (($item['PROPERTY_CUT_VALUE'] ?? '') !== '' && (int)$item['PROPERTY_CUT_VALUE'] <= 0) {
                $updateProps['CUT'] = '';
            }
            if ($updateProps) {
                CIBlockElement::SetPropertyValuesEx($item['ID'], $item['IBLOCK_ID'], $updateProps);
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($item['IBLOCK_ID'], $item['ID']);
            }
        }
    }
}
