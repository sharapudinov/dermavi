<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Catalog\Diamond;

/*
добавляем  свойство контакт владельца в инфоблок бриллиантов
*/

class AddDiamondOwnerContactsProp20200828093417211957 extends BitrixMigration
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
                'NAME'          => 'Телефон владельца',
                'SORT'          => 500,
                'CODE'          => 'OWNER_PHONE',
                'PROPERTY_TYPE' => 'S', // строка
                'MULTIPLE'      => 'N',
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );
        $propId = $this->addIblockElementProperty(
            [
                'NAME'          => 'Email владельца',
                'SORT'          => 500,
                'CODE'          => 'OWNER_EMAIL',
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

        $this->deleteIblockElementPropertyByCode($iblockId, 'OWNER_PHONE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'OWNER_EMAIL');
    }
}
