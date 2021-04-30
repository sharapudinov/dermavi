<?php

use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class CatalogAddLengthWidthHeight20200829093203456243 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode(Diamond::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME' => 'Длина/Length, мм',
                'SORT' => 500,
                'CODE' => 'LENGTH_MM',
                'PROPERTY_TYPE' => 'N',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Ширина/Width, мм',
                'SORT' => 500,
                'CODE' => 'WIDTH_MM',
                'PROPERTY_TYPE' => 'N',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Высота/Height, мм',
                'SORT' => 500,
                'CODE' => 'HEIGHT_MM',
                'PROPERTY_TYPE' => 'N',
                'MULTIPLE' => 'N',
                'IS_REQUIRED' => 'N',
                'IBLOCK_ID' => $iblockId,
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
        $iblockId = $this->getIblockIdByCode(Diamond::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($iblockId, 'LENGTH_MM');
        $this->deleteIblockElementPropertyByCode($iblockId, 'WIDTH_MM');
        $this->deleteIblockElementPropertyByCode($iblockId, 'HEIGHT_MM');

        return true;
    }
}
