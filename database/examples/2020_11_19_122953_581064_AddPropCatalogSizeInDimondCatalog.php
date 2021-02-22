<?php

use App\Models\Catalog\HL\CatalogSize;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPropCatalogSizeInDimondCatalog20201119122953581064 extends BitrixMigration
{
    private $iblockCode = 'diamond';
    private $propCode = 'CATALOG_SIZE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode($this->iblockCode);

        $this->addIblockElementProperty([
            "NAME" => "Размер (внутренний справочник)",
            "SORT" => "500",
            "CODE" => $this->propCode,
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "directory",
            "USER_TYPE_SETTINGS" => [
                "size" => "1",
                "width" => "0",
                "group" => "N",
                "multiple" => "N",
                "TABLE_NAME" => CatalogSize::getTableName(),
            ],
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "N",
            "IBLOCK_ID" => $iblockId,
        ]);

        $this->addIblockElementProperty([
            "NAME" => "Размер (внутренний справочник) (сортировка)",
            "SORT" => "500",
            "CODE" => $this->propCode."_SORT",
            "PROPERTY_TYPE" => "N",
            "MULTIPLE" => "N",
            "IS_REQUIRED" => "N",
            "IBLOCK_ID" => $iblockId,
        ]);

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode($this->iblockCode);
        $this->deleteIblockElementPropertyByCode($iblockId, $this->propCode);
        $this->deleteIblockElementPropertyByCode($iblockId, $this->propCode."_SORT");
    }
}
