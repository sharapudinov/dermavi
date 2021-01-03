<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Iblock\InheritedProperty;

class UpdateIblockDiamondsSeo20190620171251359160 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $sectionTitle = "en: Alrosa Diamonds: Certified loose diamonds from worlds diamonds mining company, #shape# #props# page №#page# | ru: Бриллианты АЛРОСА: Сертифицированные бриллианты от мирового лидера алмазодобывающей отрасли, #shape# #props# страница №#page#";
        $sectionDescription = "en: Discover the largest selection of loose GIA certified, conflict free diamonds directly from world's number one diamonds mining company | ru: Откройте для себя самый большой выбор сертифицированных бриллиантов высочайшего качества, любой из которых можете купить не выходя из дома";
        $sectionKeywords = "en: loose diamonds, special diamonds collections | ru: сертифицированные бриллианты";
        $elementMetaTitle = "en: Alrosa Diamond {=this.property.WEIGHT}-carat {=this.property.SHAPE} cut diamond, color {=this.property.COLOR}, clarity {=this.property.CLARITY}, {=this.Name} | ru: {=this.property.WEIGHT}-карат {=this.property.SHAPE} форма огранки, цвет {=this.property.COLOR}, прозрачность {=this.property.CLARITY}, {=this.Name}";
        $elementPageTitle = "en: {=this.property.WEIGHT}-carat {=this.property.SHAPE} cut diamond, color {=this.property.COLOR}, clarity {=this.property.CLARITY}, {=this.Name} | ru: {=this.property.WEIGHT}-карат {=this.property.SHAPE} форма огранки, цвет {=this.property.COLOR}, прозрачность {=this.property.CLARITY}, {=this.Name}";
        $elementDescription = "en: This GIA certified {=this.property.WEIGHT} carat, {=this.property.SHAPE} cut diamond has #cut# proportions | ru: Этот прекрасный бриллиант {=this.property.WEIGHT} карат, {=this.property.SHAPE} форма, имеет #cut# огранку";
        $elementKeywords = "en: {=this.property.SHAPE} diamond, color {=this.property.COLOR} | ru: бриллиант формы {=this.property.SHAPE}, цвет {=this.property.COLOR}";

        $iblockId = $this->getIblockIdByCode('diamond');
        $ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
        $ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
        $ipropIblockValues->clearValues();

        $arFields["IPROPERTY_TEMPLATES"] = array(
            "SECTION_META_TITLE" => $sectionTitle,
            "SECTION_META_KEYWORDS" => $sectionKeywords,
            "SECTION_META_DESCRIPTION" => $sectionDescription,
            "SECTION_PAGE_TITLE" => $sectionTitle,
            "ELEMENT_META_TITLE" => $elementMetaTitle,
            "ELEMENT_META_KEYWORDS" => $elementKeywords,
            "ELEMENT_META_DESCRIPTION" => $elementDescription,
            "ELEMENT_PAGE_TITLE" => $elementPageTitle,
        );

        $newTemplates = $arFields["IPROPERTY_TEMPLATES"];
        $ipropIblockTemplates->set($newTemplates);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $sectionMetaTitle = "Certified loose diamonds from the world's leading mining company";
        $sectionPageTitle = "en: Certified loose diamonds from word's diamonds mining company, #props# | ru: Cертифицированные бриллианты от мирового лидера алмазодобывающей отрасли, #props#";
        $sectionDescription = "Discover the largest selection of loose GIA certified, conflict free diamonds directly from world's number one diamonds mining company";
        $sectionKeywords = "loose diamonds, special diamonds collections";
        $elementMetaTitle = "en: {=this.property.WEIGHT} carat, {=this.property.SHAPE} cut diamond, color {=this.property.COLOR}, clarity {=this.property.CLARITY}, {=this.Name} | ru: {=this.property.WEIGHT} карат, {=this.property.SHAPE} форма огранки, цвет {=this.property.COLOR}, прозрачность {=this.property.CLARITY}, {=this.Name}";
        $elementPageTitle = "en: {=this.property.WEIGHT}-carat {=this.property.SHAPE} cut diamond, color {=this.property.COLOR}, clarity {=this.property.CLARITY}, {=this.Name} | ru: {=this.property.WEIGHT}-карат {=this.property.SHAPE} форма огранки, цвет {=this.property.COLOR}, прозрачность {=this.property.CLARITY}, {=this.Name}";
        $elementDescription = "en: This GIA certified  {=this.property.WEIGHT} carat, {=this.property.SHAPE} cut diamond has  perfect proportions | ru: Этот прекрасный бриллиант {=this.property.WEIGHT} карат, {=this.property.SHAPE} форма, имеет идеальную огранку";
        $elementKeywords = "en: {=this.property.SHAPE} diamond, color {=this.property.COLOR} | ru: бриллиант формы {=this.property.SHAPE}, цвет {=this.property.COLOR}";

        $iblockId = $this->getIblockIdByCode('diamond');
        $ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
        $ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
        $ipropIblockValues->clearValues();

        $arFields["IPROPERTY_TEMPLATES"] = array(
            "SECTION_META_TITLE" => $sectionMetaTitle,
            "SECTION_META_KEYWORDS" => $sectionKeywords,
            "SECTION_META_DESCRIPTION" => $sectionDescription,
            "SECTION_PAGE_TITLE" => $sectionPageTitle,
            "ELEMENT_META_TITLE" => $elementMetaTitle,
            "ELEMENT_META_KEYWORDS" => $elementKeywords,
            "ELEMENT_META_DESCRIPTION" => $elementDescription,
            "ELEMENT_PAGE_TITLE" => $elementPageTitle,
        );

        $newTemplates = $arFields["IPROPERTY_TEMPLATES"];
        $ipropIblockTemplates->set($newTemplates);
    }
}
