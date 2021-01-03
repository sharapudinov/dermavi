<?php

use App\Models\Catalog\HL\Office;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class Offices20201012130548766055 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     * @throws ArgumentException
     *
     * @return mixed
     */
    public function up()
    {
        $valueArray = [
            '77536348-8322-4c38-99f7-43b3784179a1' => 'Moscow',
            'cb16608d-ed00-4972-982f-52c400b71c7e' => 'Moscow',
            '1ca4dfc4-5b7c-4280-8ba4-60e98ff03638' => 'Moscow',
            'a7393594-c28b-45bb-b23d-786423011a29' => 'Tel Aviv',
            '8767c3e8-3f4a-4b9e-862a-9b1298d9e24c' => 'Hong Kong',
            '013bd964-a06a-4c92-a393-b883acbf4830' => 'Antwerp',
            '1b61f8d5-c9a3-4eff-b24e-1852009ffd70' => 'Dubai',
            '846705a2-bcfd-40e5-80fb-36f4de14ef3d' => 'New York',
        ];

        /** @var DataManager $strEntityDataClass */
        $strEntityDataClass = HLblock::compileClass(Office::getTableName());
        $rsData = $strEntityDataClass::getList([]);

        while ($arItem = $rsData->Fetch()) {
            if (array_key_exists($arItem['UF_XML_ID'], $valueArray)) {
                $displayValue = $valueArray[$arItem['UF_XML_ID']];
                $arItem['UF_DISPLAY_VALUE_RU'] = $displayValue;
                $arItem['UF_DISPLAY_VALUE_EN'] = $displayValue;
                $strEntityDataClass::update($arItem['ID'], $arItem);
            }
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function down()
    {


    }
}
