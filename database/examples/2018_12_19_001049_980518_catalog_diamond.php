<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Catalog\GroupLangTable;
use Bitrix\Catalog\GroupTable;
use Bitrix\Main\Loader;

class CatalogDiamond20181219001049980518 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $typeIB = new \CIBlockType;
        $isOK = $typeIB->Add([
            "ID" => "catalog",
            "LANG" => [
                "en" => [
                    "NAME" => "Catalog",
                    "ELEMENT_NAME" => "Catalog element",
                    "SECTION_NAME" => "Catalog section",
                ],
                "ru" => [
                    "NAME" => "Каталог",
                    "ELEMENT_NAME" => "Элемент каталога",
                    "SECTION_NAME" => "Раздел каталога",
                ],
            ],
        ]);

        if (!$isOK) {
            throw new MigrationException('Ошибка при добавлении типа инфоблока ' . $typeIB->LAST_ERROR);
        }

        $ib = new \CIBlock;
        $iblockId = $ib->Add([
            "NAME" => "Бриллианты",
            "CODE" => "diamond",
            "SITE_ID" => "s1",
            "IBLOCK_TYPE_ID" => "catalog",
            'IS_CATALOG' => 'Y',
            "VERSION" => 2,
            'GROUP_ID' => ['2' => 'R'],
        ]);

        if (!$iblockId) {
            throw new MigrationException('Ошибка при добавлении инфоблока ' . $ib->LAST_ERROR);
        }

        $props = [
            [
                "NAME" => "Качество полировки",
                "SORT" => "",
                "CODE" => "POLISH",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_polish",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Качество огранки",
                "SORT" => "",
                "CODE" => "CUT",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_quality",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Кулет",
                "SORT" => "",
                "CODE" => "CULET",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_culet",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цвет",
                "SORT" => "",
                "CODE" => "COLOR",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "catalog_color",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Форма",
                "SORT" => "",
                "CODE" => "SHAPE",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "catalog_shape",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Прозрачность",
                "SORT" => "",
                "CODE" => "CLARITY",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "catalog_clarity",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Месторождение",
                "SORT" => "",
                "CODE" => "ORIGIN",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "stone_location",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Симметрия",
                "SORT" => "",
                "CODE" => "SYMMETRY",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_symmetry",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Компания-обработчик",
                "SORT" => "",
                "CODE" => "FACTORY",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_factory",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Компания-добытчик",
                "SORT" => "",
                "CODE" => "OWNERSHIP",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_ownership",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Флюоресценция",
                "SORT" => "",
                "CODE" => "FLUOR",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "catalog_fluorescence",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цвет флюоресценции",
                "SORT" => "",
                "CODE" => "FLUOR_COLOR",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "catalog_fluorescence_color",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Размер",
                "SORT" => "",
                "CODE" => "SIZE",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_size",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Место обработки бриллианта",
                "SORT" => "",
                "CODE" => "CUT_LOCATION",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "stone_location",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Огранщик",
                "SORT" => "",
                "CODE" => "CUTTER",
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "directory",
                "USER_TYPE_SETTINGS" => [
                    "size" => "1",
                    "width" => "0",
                    "group" => "N",
                    "multiple" => "N",
                    "TABLE_NAME" => "dict_persona",
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Идентификатор алмаза",
                "SORT" => "",
                "CODE" => "STONE_GUID",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Вес бриллианта",
                "SORT" => "",
                "CODE" => "WEIGHT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Год добычи алмаза",
                "SORT" => "",
                "CODE" => "YEAR_MINING",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цена за карат",
                "SORT" => "",
                "CODE" => "PRICE_CARAT",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Цена",
                "SORT" => "",
                "CODE" => "PRICE",
                "PROPERTY_TYPE" => "N",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Номер GIA сертификата",
                "SORT" => "",
                "CODE" => "GIA_CERT",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Номер местного сертификата",
                "SORT" => "",
                "CODE" => "RF_CERT",
                "PROPERTY_TYPE" => "S",
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Наличие виджета",
                "SORT" => "",
                "CODE" => "HAS_WIDGET",
                "PROPERTY_TYPE" => "L",
                "VALUES" => [
                    [
                        "VALUE" => "Y",
                        "DEF" => "N",
                        "SORT" => "100"
                    ]
                ],
                "MULTIPLE" => "N",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
            ],
            [
                "NAME" => "Рекомендуемые с этим товары",
                "SORT" => "",
                "CODE" => "RECOMMENDED_PRODUCTS",
                "MULTIPLE" => "Y",
                "IS_REQUIRED" => "N",
                "IBLOCK_ID" => $iblockId,
                "LINK_IBLOCK_ID" => $iblockId
            ]
        ];

        foreach ($props as $prop) {
            $this->addIblockElementProperty($prop);
        }

        Loader::includeModule("catalog");
        $rGroups = GroupTable::getList();
        while ($group = $rGroups->fetch()) {
            GroupTable::delete($group['ID']);
        }

        $result = GroupTable::add([
            "fields" => [
                "NAME" => "SALE_COST",
                "BASE" => "Y",
                "XML_ID" => "SALE_COST",
            ],
        ]);

        $langGroups = [
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "ru",
                "NAME" => "Цена для ЮЛ",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "en",
                "NAME" => "Legal entity price",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "cn",
                "NAME" => "Legal entity price",
            ],
        ];

        $result = GroupTable::add([
            "fields" => [
                "NAME" => "INDIVIDUAL_PRICE",
                "BASE" => "N",
                "XML_ID" => "INDIVIDUAL_PRICE",
            ],
        ]);

        $langGroups = [
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "ru",
                "NAME" => "Цена для ФЛ",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "en",
                "NAME" => "Individual price",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "cn",
                "NAME" => "Individual price",
            ],
        ];

        foreach ($langGroups as $groupLang) {
            GroupLangTable::add($groupLang);
        }

        $result = GroupTable::add([
            "fields" => [
                "NAME" => "SALE_COST_CARAT",
                "BASE" => "N",
                "XML_ID" => "SALE_COST_CARAT",
            ],
        ]);

        $langGroups = [
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "ru",
                "NAME" => "Цена за карат для ЮЛ",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "en",
                "NAME" => "Carat legal entity price",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "cn",
                "NAME" => "Carat legal entity price",
            ],
        ];

        foreach ($langGroups as $groupLang) {
            GroupLangTable::add($groupLang);
        }

        $result = GroupTable::add([
            "fields" => [
                "NAME" => "CARAT_INDIVIDUAL_PRICE",
                "BASE" => "N",
                "XML_ID" => "CARAT_INDIVIDUAL_PRICE",
            ],
        ]);

        $langGroups = [
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "ru",
                "NAME" => "Цена за карат для ФЛ",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "en",
                "NAME" => "Carat individual price",
            ],
            [
                "CATALOG_GROUP_ID" => $result->getId(),
                "LANG" => "cn",
                "NAME" => "Carat individual price",
            ],
        ];

        foreach ($langGroups as $groupLang) {
            GroupLangTable::add($groupLang);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->deleteIblockByCode('diamond');
        \CIBlockType::Delete('catalog');
    }
}
