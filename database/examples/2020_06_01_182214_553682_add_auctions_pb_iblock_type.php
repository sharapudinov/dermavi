<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания типа ИБ "Аукционы PB"
 * Class AddAuctionsPbIblockType20200601182214553682
 */
class AddAuctionsPbIblockType20200601182214553682 extends BitrixMigration
{
    /** @var string $iblockTypeId - Символьный код типа инфоблока */
    private $iblockTypeId = 'auctions_pb';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockType)->Add([
            'ID' => $this->iblockTypeId,
            'SECTIONS' => 'N',
            'LANG' => [
                'en' => [
                    'NAME' => 'Auctions PB'
                ],
                'ru' => [
                    'NAME' => 'Аукционы PB'
                ],
                'cn' => [
                    'NAME' => 'Auctions PB'
                ]
            ]
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
        CIBlockType::Delete(CIBlockType::GetList([], ['ID' => $this->iblockTypeId])->Fetch()['ID']);
    }
}
