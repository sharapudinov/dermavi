<?php

use App\Core\BitrixProperty\Property;
use App\Helpers\LanguageHelper;
use App\Models\Banners\SliderBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойств в ИБ "Баннеры для слайдера"
 * Class UpdateSliderBannerIblock20200903165824113866
 */
class UpdateSliderBannerIblock20200903165824113866 extends BitrixMigration
{
    /** @var string $imageMainCode Символьный код свойства "Изображение (основное)" */
    private $imageMainCode = 'IMAGE_MAIN';

    /** @var string $imageMainCode Символьный код свойства "Изображение (адаптив)" */
    private $imageAdaptiveCode = 'IMAGE_ADAPTIVE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        return;
        $property = new Property(SliderBanner::iblockId());
        $property->addPropertyToQuery($this->imageMainCode);
        $property->addPropertyToQuery($this->imageAdaptiveCode);
        $propertiesInfo = $property->getPropertiesInfo();

        // --- Свойства доступности в версиях --- //
        (new CIBlockProperty())->add([
            'NAME' => 'Показывать на русской версии',
            'CODE' => 'SHOW_ON_RU',
            'SORT' => '494',
            'IBLOCK_ID' => SliderBanner::iblockId(),
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Показывать на английской версии',
            'CODE' => 'SHOW_ON_EN',
            'SORT' => '495',
            'IBLOCK_ID' => SliderBanner::iblockId(),
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Показывать на китайской версии',
            'CODE' => 'SHOW_ON_CN',
            'SORT' => '496',
            'IBLOCK_ID' => SliderBanner::iblockId(),
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
        ]);
        // --- Свойства доступности в версиях --- //

        // --- Свойства изображений --- //
        (new CIBlockProperty())->Update($propertiesInfo[$this->imageMainCode]['PROPERTY_ID'], [
            'NAME' => 'Изображение основное (рус)',
            'CODE' => $this->imageMainCode . '_RU',
            'IS_REQUIRED' => 'N',
            'SORT' => '497'
        ]);
        (new CIBlockProperty())->Update($propertiesInfo[$this->imageAdaptiveCode]['PROPERTY_ID'], [
            'NAME' => 'Изображение адаптив (рус)',
            'CODE' => $this->imageAdaptiveCode . '_RU',
            'IS_REQUIRED' => 'N',
            'SORT' => '498'
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Изображение основное (англ)',
            'CODE' => $this->imageMainCode . '_EN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '499',
            'IBLOCK_ID' => SliderBanner::iblockId()
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Изображение адаптив (англ)',
            'CODE' => $this->imageAdaptiveCode . '_EN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '500',
            'IBLOCK_ID' => SliderBanner::iblockId()
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Изображение основное (кит)',
            'CODE' => $this->imageMainCode . '_CN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '501',
            'IBLOCK_ID' => SliderBanner::iblockId()
        ]);

        (new CIBlockProperty())->add([
            'NAME' => 'Изображение адаптив (кит)',
            'CODE' => $this->imageAdaptiveCode . '_CN',
            'PROPERTY_TYPE' => 'F',
            'SORT' => '502',
            'IBLOCK_ID' => SliderBanner::iblockId()
        ]);
        // --- Свойства изображений --- //
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = new Property(SliderBanner::iblockId());
        foreach (LanguageHelper::getAvailableLanguages() as $language) {
            $property->addPropertyToQuery($this->imageMainCode . '_' . strtoupper($language));
            $property->addPropertyToQuery($this->imageAdaptiveCode . '_' . strtoupper($language));
            $property->addPropertyToQuery('SHOW_ON_' . strtoupper($language));
        }
        $propertiesInfo = $property->getPropertiesInfo();

        CIBlockProperty::Delete($propertiesInfo['SHOW_ON_RU']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo['SHOW_ON_EN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo['SHOW_ON_CN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo[$this->imageMainCode . '_EN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo[$this->imageMainCode . '_CN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo[$this->imageAdaptiveCode . '_EN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo[$this->imageAdaptiveCode . '_CN']['PROPERTY_ID']);

        (new CIBlockProperty())->Update($propertiesInfo[$this->imageMainCode . '_RU']['PROPERTY_ID'], [
            'NAME' => 'Изображение (основное)',
            'CODE' => $this->imageMainCode,
            'IS_REQUIRED' => 'Y',
            'SORT' => '501'
        ]);
        (new CIBlockProperty())->Update($propertiesInfo[$this->imageAdaptiveCode . '_RU']['PROPERTY_ID'], [
            'NAME' => 'Изображение (адаптив)',
            'CODE' => $this->imageAdaptiveCode,
            'IS_REQUIRED' => 'Y',
            'SORT' => '502'
        ]);
    }
}
