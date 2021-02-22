<?php

use App\Models\Jewelry\JewelrySection;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockJewelrySeo20200730133549279707 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $elementMetaTitle = '{=this.property.NAME_RU} из коллекции {=this.property.COLLECTION_ID} {=concat {=distinct this.catalog.sku.property.METAL_COLOR_ID ", "}} {=concat {=distinct this.catalog.sku.property.METAL_ID ", "}} {=concat {=distinct this.catalog.sku.property.FINENESS_ID ", "}}  - купить {=this.property.NAME_RU} в интернет-магазине АЛРОСА.';
        $elementPageTitle = '{=this.property.NAME_RU} из коллекции {=this.property.COLLECTION_ID} {=concat {=distinct this.catalog.sku.property.METAL_COLOR_ID ", "}} {=concat {=distinct this.catalog.sku.property.METAL_ID ", "}} {=concat {=distinct this.catalog.sku.property.FINENESS_ID ", "}}  - купить {=this.property.NAME_RU} в интернет-магазине АЛРОСА.';
        $elementDescription = '{=this.property.NAME_RU} из коллекции {=this.property.COLLECTION_ID} {=concat {=distinct this.catalog.sku.property.METAL_COLOR_ID ", "}} {=concat {=distinct this.catalog.sku.property.METAL_ID ", "}} {=concat {=distinct this.catalog.sku.property.FINENESS_ID ", "}}. Интернет-магазин АЛРОСА предоставляет услугу гравировки украшений - прекрасный способ подчеркнуть ценность украшения.';
        $elementKeywords = '{=this.property.NAME_RU}, {=concat {=distinct this.catalog.sku.property.METAL_COLOR_ID ", "}} {=concat {=distinct this.catalog.sku.property.METAL_ID ", "}} {=concat {=distinct this.catalog.sku.property.FINENESS_ID ", "}}, {=this.property.COLLECTION_ID}';

        $iblockId = $this->getIblockIdByCode('app_jewelry');
        $ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
        $ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
        $ipropIblockValues->clearValues();

        $arFields["IPROPERTY_TEMPLATES"] = array(
            "ELEMENT_META_TITLE" => $elementMetaTitle,
            "ELEMENT_META_KEYWORDS" => $elementKeywords,
            "ELEMENT_META_DESCRIPTION" => $elementDescription,
            "ELEMENT_PAGE_TITLE" => $elementPageTitle,
        );

        $newTemplates = $arFields["IPROPERTY_TEMPLATES"];
        $ipropIblockTemplates->set($newTemplates);

        $arrSections = [];
        $sections = \CIBlockSection::GetList(false, ['IBLOCK_ID' => $iblockId], []);
        while($ar_res = $sections->Fetch()) {
            $arrSections[] = ['id' => $ar_res['ID'], 'code' => $ar_res['CODE']];
        }


        $seoPropSections = [
            'pendants' => [
                'title' => 'Подвески с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные подвески из желтого, белого и красного золота с бриллиантами. Подберите подвеску по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Подвески с бриллиантами, кулоны с бриллиантами, бриллиантовые подвески, бриллиантовые кулоны, золотые подвески, золотые кулоны.'
            ],
            'rings' => [
                'title' => 'Кольца с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные обручальные и помолвочные кольца с бриллиантами. Подберите кольцо по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Кольца с бриллиантами, бриллиантовые кольца, обручальные кольца, помолвочные кольца, золотые кольца.'
            ],
            'earrings' => [
                'title' => 'Серьги с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные серьги из желтого, белого и красного золота с бриллиантами. Подберите серьги по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Серьги с бриллинтами, бриллиантовые серьги, сережки с бриллиантами, бриллиантовые сережки, золотые серьги.'
            ],
            'pusets' => [
                'title' => 'Пусеты с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные пусеты из желтого, белого и красного золота с бриллиантами. Подберите пусеты по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Пусеты с бриллиантами, бриллиантовые пусеты, гвоздики с бриллиантами, бриллиантовые гвоздики, золотые пусеты, золотые гвоздики.'
            ],
            'bracelet' => [
                'title' => 'Браслеты с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные браслеты из желтого, белого и красного золота с бриллиантами. Подберите браслет по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Браслеты с бриллиантами, бриллиантовые браслеты, золотые браслеты.'
            ],
            'brooches' => [
                'title' => 'Броши с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные броши из желтого, белого и красного золота с бриллиантами. Подберите брошь по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Броши с бриллиантами, бриллиантовые броши, золотая брошь.'
            ],
            'badge' => [
                'title' => 'Значки с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные значки из желтого, белого и красного золота с бриллиантами. Подберите значок по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Значки с бриллиантами, бриллиантовые значки, золотые значки.'
            ],
            'tiestickpin' => [
                'title' => 'Заколки для галстука с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные заколки для галстука из желтого, белого и красного золота с бриллиантами. Подберите заколку для галстука по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Заколки для галстука с бриллиантами, бриллиантовые заколки для галстука, золотые заколки для галстука. '
            ],
            'necklace' => [
                'title' => 'Колье с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные колье из желтого, белого и красного золота с бриллиантами. Подберите колье по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Колье с бриллиантами, бриллиантовое колье, золотое колье.'
            ],
            'cufflinks' => [
                'title' => 'Запонки с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные запонки из желтого, белого и красного золота с бриллиантами. Подберите запонки по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Запонки с бриллиантами, бриллиантовые запонки, золотые запонки.'
            ],
            'tieclip' => [
                'title' => 'Зажимы для галстука с бриллиантами - купить в интернет-магазине АЛРОСА.',
                'description' => 'Эксклюзивные зажимы для галстука из желтого, белого и красного золота с бриллиантами. Подберите зажимы для галстука по выгодной цене в интернет-магазине АЛРОСА.',
                'keywords' => 'Зажимы для галстука с бриллиантами, бриллиантовые зажимы для галстука, золотые зажимы для галстука. '
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
            ));
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
        $ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
        $ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
        $ipropIblockValues->clearValues();

        $arFields["IPROPERTY_TEMPLATES"] = array(
            "ELEMENT_META_TITLE" => '',
            "ELEMENT_META_KEYWORDS" => '',
            "ELEMENT_META_DESCRIPTION" => '',
            "ELEMENT_PAGE_TITLE" => '',
        );

        $newTemplates = $arFields["IPROPERTY_TEMPLATES"];
        $ipropIblockTemplates->set($newTemplates);

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
                "SECTION_META_KEYWORDS" => '',
                "SECTION_META_DESCRIPTION" => '',
            ));
        }
    }
}
