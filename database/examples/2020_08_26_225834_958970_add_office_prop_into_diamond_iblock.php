<?php

use App\Models\Catalog\Catalog;
use App\Models\Catalog\HL\Office;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

/**
 * Класс, описывающий миграцию для создания свойства "Локация" в ИБ "Бриллианты"
 * Class AddOfficePropIntoDiamondIblock20200826225834958970
 */
class AddOfficePropIntoDiamondIblock20200826225834958970 extends BitrixMigration
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
                "NAME"               => "Локация",
                "SORT"               => "500",
                "CODE"               => "OFFICE",
                "PROPERTY_TYPE"      => "S",
                "USER_TYPE"          => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size"       => "1",
                    "width"      => "0",
                    "group"      => "N",
                    "multiple"   => "N",
                    "TABLE_NAME" => Office::getTableName(),
                ],
                "MULTIPLE"           => "N",
                "IS_REQUIRED"        => "N",
                "IBLOCK_ID"          => $iblockId,
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

        $this->deleteIblockElementPropertyByCode($iblockId, 'OFFICE');
    }
}
