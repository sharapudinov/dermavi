<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания инфоблока "Правила аукциона PB"
 * Class AddAuctionsPbRulesIblock20200601200809913139
 */
class AddAuctionsPbRulesIblock20200601200809913139 extends BitrixMigration
{
    /** @var string $iblockCode - Символьный код ИБ */
    private $iblockCode = 'auction_pb_rule';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var int $auctionRuleIblockId - Идентификатор инфоблока "Правила аукционов" */
        $auctionRuleIblockId = (new CIBlock)->Add([
            'NAME' => 'Правила аукционов',
            'CODE' => $this->iblockCode,
            'VERSION' => 2,
            'IBLOCK_TYPE_ID' => 'auctions_pb',
            'SITE_ID' => ['s1', 's2', 's3']
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (рус)',
            'CODE' => 'DESCRIPTION_RU',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'IS_REQUIRED' => 'Y',
            'SORT' => '500',
            'IBLOCK_ID' => $auctionRuleIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (англ)',
            'CODE' => 'DESCRIPTION_EN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'IS_REQUIRED' => 'Y',
            'SORT' => '501',
            'IBLOCK_ID' => $auctionRuleIblockId
        ]);

        (new CIBlockProperty)->Add([
            'NAME' => 'Описание (кит)',
            'CODE' => 'DESCRIPTION_СN',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'HTML',
            'SORT' => '502',
            'IBLOCK_ID' => $auctionRuleIblockId
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
