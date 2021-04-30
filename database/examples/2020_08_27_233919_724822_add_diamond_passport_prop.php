<?php

use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddDiamondPassportProp20200827233919724822 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode(Diamond::IBLOCK_CODE);

        $propId = $this->addIblockElementProperty(
            [
                'NAME'          => 'Паспорт бриллианта',
                'SORT'          => 500,
                'CODE'          => 'DIAMOND_PASSPORT',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'N',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode(Diamond::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($iblockId, 'DIAMOND_PASSPORT');
    }
}
