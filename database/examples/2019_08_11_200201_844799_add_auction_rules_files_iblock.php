<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания инфоблока "Файлы правил аукционов"
 * Class AddAuctionRulesFilesIblock20190811200201844799
 */
class AddAuctionRulesFilesIblock20190811200201844799 extends BitrixMigration
{
    /** @var string $iblockCode - Символьный код ИБ */
    private $iblockCode = 'auction_rule_file';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var int $auctionRuleFileIblockId - Идентификатор инфоблока "Файлы правил аукционов" */
        $auctionRuleFileIblockId = (new CIBlock)->Add([
            'NAME' => 'Файлы правил аукционов',
            'CODE' => $this->iblockCode,
            'IBLOCK_TYPE_ID' => 'auctions',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (рус)',
            'CODE' => 'NAME_RU',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (англ)',
            'CODE' => 'NAME_EN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'Y',
            'SORT' => '501',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Название (кит)',
            'CODE' => 'NAME_CN',
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => 'N',
            'SORT' => '502',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (рус)',
            'CODE' => 'FILE_RU',
            'PROPERTY_TYPE' => 'F',
            'IS_REQUIRED' => 'Y',
            'SORT' => '503',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (англ)',
            'CODE' => 'FILE_EN',
            'PROPERTY_TYPE' => 'F',
            'IS_REQUIRED' => 'Y',
            'SORT' => '504',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Файл (кит)',
            'CODE' => 'FILE_EN',
            'PROPERTY_TYPE' => 'F',
            'IS_REQUIRED' => 'N',
            'SORT' => '505',
            'IBLOCK_ID' => $auctionRuleFileIblockId
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->deleteIblockByCode($this->iblockCode);
    }
}
