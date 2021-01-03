<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class PaidService20201013130256042842 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('paid_service');
        $propertyCode = 'DELIVERY_DAYS';
        $propertyValue = 7;

        $propId = $this->addIblockElementProperty([
            'NAME' => 'Увеличение срока доставки (дней)',
            'SORT' => 500,
            'CODE' => $propertyCode,
            'PROPERTY_TYPE' => 'N', // Список
            'LIST_TYPE' => 'C', // Тип списка - 'флажки'
            'VALUES' => [
                'VALUE' => '0',
            ],
            'MULTIPLE'  => 'N',
            'IS_REQUIRED' => 'N',
            'IBLOCK_ID' => $iblockId
        ]);

        $res = \CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => $iblockId, 'CODE' => ['engraving', 'engraving-jewelry']],
            false,
            false,
            ["ID"]
        );

        while ($element = $res->GetNext()) {
            (new CIBlockElement)->SetPropertyValues($element['ID'], $iblockId, $propertyValue, $propertyCode);
        }

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode('paid_service');

        $this->deleteIblockElementPropertyByCode($iblockId, 'DELIVERY_DAYS');
    }
}
