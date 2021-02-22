<?php

use App\Models\Jewelry\JewelrySection;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateJewelrySeo20200903145438833489 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode('app_jewelry');

        $arrSections = [];
        $sections = \CIBlockSection::GetList(false, ['IBLOCK_ID' => $iblockId], []);
        while($ar_res = $sections->Fetch()) {
            $arrSections[] = ['id' => $ar_res['ID'], 'code' => $ar_res['CODE']];
        }


        $seoPropSections = [
            'pendants' => [
                'title' => 'Подвески с бриллиантами: купить золотую подвеску | Москва и регионы',
                'description' => 'Эксклюзивные подвески из золота с бриллиантами &#128142; Купить золотую подвеску в интернет-магазине. Выгодные цены. Доставка: Москва и регионы России.',
                'name' => 'Подвески с бриллиантами'
            ],
            'rings' => [
                'title' => 'Кольца с бриллиантами: купить золотое кольцо с бриллиантом | Москва и регионы',
                'description' => 'Обручальные и помолвочные кольца из золота с бриллиантами. Купить золотое кольцо с бриллиантом в интернет-магазине &#128142; Доставка: Москва и регионы.',
                'name' => 'Кольца с бриллиантами'
            ],
            'earrings' => [
                'title' => 'Серьги с бриллиантами: купить золотые серьги с бриллиантами',
                'description' => 'Эксклюзивные серьги из золота с бриллиантами. Купить бриллиантовые сережки в интернет-магазине. Выгодные цены &#128142; Доставка: Москва, регионы России.',
                'name' => 'Серьги с бриллиантами'
            ],
            'pusets' => [
                'title' => 'Пусеты с бриллиантами: купить золотые пусеты, цены | Москва и регионы',
                'description' => 'Эксклюзивные пусеты из золота с бриллиантами &#128142; Купить золотые пусеты в интернет-магазине. Выгодные цены. Доставка: Москва и регионы России.',
                'name' => 'Пусеты с бриллиантами'
            ],
            'bracelet' => [
                'title' => 'Браслеты с бриллиантами: купить браслет из золота | Москва и регионы',
                'description' => 'Эксклюзивные браслеты из золота с бриллиантами &#128142; Купить золотой браслет с бриллиантом в интернет-магазине. Выгодные цены. Доставка: Москва и регионы.',
                'name' => 'Браслеты с бриллиантами'
            ],
            'brooches' => [
                'title' => 'Броши с бриллиантами: купить золотую брошь | Москва и регионы',
                'description' => 'Эксклюзивные броши из золота с бриллиантами. Купить золотую брошь с бриллиантом в интернет-магазине. Выгодные цены &#128142; Доставка: Москва и регионы России.',
                'name' => 'Броши с бриллиантами'
            ],
            'badge' => [
                'title' => 'Значки с бриллиантами: купить значки в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные значки из желтого, белого и красного золота с бриллиантами &#128142; Подберите значок по выгодной цене. Доставка: Москва и регионы России.',
                'name' => 'Значки с бриллиантами'
            ],
            'tiestickpin' => [
                'title' => 'Заколки с бриллиантами: купить золотую заколку | Москва и регионы',
                'description' => 'Эксклюзивные заколки из золота с бриллиантами &#128142; Купить золотую заколку в интернет-магазине. Выгодные цены. Доставка: Москва и регионы России.',
                'name' => 'Заколки с бриллиантами'
            ],
            'necklace' => [
                'title' => 'Колье с бриллиантами: купить золотое колье | Москва и регионы',
                'description' => 'Эксклюзивные колье из золота с бриллиантами. Купить золотое колье с бриллиантами в интернет-магазине. Выгодные цены &#128142; Доставка: Москва, регионы России.',
                'name' => 'Колье с бриллиантами'
            ],
            'cufflinks' => [
                'title' => 'Запонки с бриллиантами: купить золотые запонки | Москва и регионы',
                'description' => 'Эксклюзивные запонки из золота с бриллиантами &#128142; Купить золотые запонки с бриллиантами в интернет-магазине. Выгодные цены. Доставка: Москва и регионы.',
                'name' => 'Запонки с бриллиантами'
            ],
            'tieclip' => [
                'title' => 'Зажимы с бриллиантами: купить золотой зажим для галстука',
                'description' => 'Эксклюзивные зажимы для галстука из золота с бриллиантами. Купить золотой зажим с бриллиантом в интернет-магазине &#128142; Доставка: Москва и регионы.',
                'name' => 'Зажимы с бриллиантами'
            ],
        ];

        foreach ($arrSections as $section) {
            $ipropTemplates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($iblockId, $section['id']);
            $ipropSectionValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblockId, $section['id']);
            $ipropSectionValues->clearValues();
            $ipropTemplates->set(array(
                "SECTION_META_TITLE" => $seoPropSections[$section['code']]['title'],
                "SECTION_META_KEYWORDS" => $seoPropSections[$section['code']]['keywords'],
                "SECTION_META_DESCRIPTION" => $seoPropSections[$section['code']]['description'],
                "UF_NAME_RU" => $seoPropSections[$section['code']]['name'],
            ));

            $get_fields = CIBlockSection::GetList(
                array(),
                array(
                    'IBLOCK_ID' => $iblockId,
                    'ID' => $section['id']
                ),
                false,
                array(
                    'UF_NAME_RU'
                )
            );
            if($get_fields_item = $get_fields->GetNext()) { 
                $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_43_SECTION", $section['id'], Array('UF_NAME_RU' => $seoPropSections[$section['code']]['name']));   
            }
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
        $iblockId = $this->getIblockIdByCode('app_jewelry');

        $arrSections = [];
        $sections = \CIBlockSection::GetList(false, ['IBLOCK_ID' => $iblockId], []);
        while($ar_res = $sections->Fetch()) {
            $arrSections[] = ['id' => $ar_res['ID'], 'code' => $ar_res['CODE']];
        }

        foreach ($arrSections as $section) {
            $ipropTemplates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($iblockId, $section['id']);
            $ipropSectionValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblockId, $section['id']);
            $ipropSectionValues->clearValues();
            $ipropTemplates->set(array(
                "SECTION_META_TITLE" => '',
                "SECTION_META_DESCRIPTION" => '',
            ));

            $get_fields = CIBlockSection::GetList(
                array(),
                array(
                    'IBLOCK_ID' => $iblockId,
                    'ID' => $section['id']
                ),
                false,
                array(
                    'UF_NAME_RU'
                )
            );
            if($get_fields_item = $get_fields->GetNext()) { 
                $GLOBALS["USER_FIELD_MANAGER"]->Update("IBLOCK_43_SECTION", $section['id'], Array('UF_NAME_RU' => ''));   
            }
        }
    }
}
