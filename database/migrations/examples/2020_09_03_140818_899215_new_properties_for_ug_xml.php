<?php

use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

class NewPropertiesForUgXml20200903140818899215 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode(JewelrySku::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME' => 'Тип камня вставки',
                'SORT' => 554,
                'CODE' => 'STONE_TYPE',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Цвет камня вставки',
                'SORT' => 555,
                'CODE' => 'COLOR_TEXT',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Цвет камня вставки (GIA)',
                'SORT' => 556,
                'CODE' => 'COLOR_TEXT_GIA',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Форма огранки вставки',
                'SORT' => 557,
                'CODE' => 'SHAPE_TEXT',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Чистота вставки',
                'SORT' => 558,
                'CODE' => 'CLARITY_TEXT',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Чистота вставки (GIA)',
                'SORT' => 559,
                'CODE' => 'CLARITY_TEXT_GIA',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'Y',
                'MULTIPLE_CNT'      => '1',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode(JewelrySku::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($iblockId, 'STONE_TYPE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'COLOR_TEXT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'COLOR_TEXT_GIA');
        $this->deleteIblockElementPropertyByCode($iblockId, 'SHAPE_TEXT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CLARITY_TEXT');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CLARITY_TEXT_GIA');

        return true;
    }
}
