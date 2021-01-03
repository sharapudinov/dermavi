<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для создания типа ИБ "Аукционы"
 * Class AddAuctionsIblockType20190811182214553682
 */
class AddAuctionsIblockType20190811182214553682 extends BitrixMigration
{
    /** @var string $iblockTypeId - Символьный код типа инфоблока */
    private $iblockTypeId = 'auctions';

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
                    'NAME' => 'Auctions'
                ],
                'ru' => [
                    'NAME' => 'Аукционы'
                ],
                'cn' => [
                    'NAME' => 'Auctions'
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
