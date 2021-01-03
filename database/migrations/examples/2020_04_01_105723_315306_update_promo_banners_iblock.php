<?php

use App\Core\BitrixProperty\Property;
use App\Models\Main\PromoBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления ИБ "Промо-баннер"
 * Class UpdatePromoBannersIblock20200401105723315306
 */
class UpdatePromoBannersIblock20200401105723315306 extends BitrixMigration
{
    /** @var array|string Символьнйы код свойства для обновления */
    private const PROPERTIES = [
        'BACKGROUND_IMAGE',
        'INFOGRAPHICS_IMAGES',
        'DESCRIPTION_RU',
        'DESCRIPTION_EN',
        'DESCRIPTION_CN'
    ];

    /**
     * Обновляет свойство
     *
     * @param bool $required Флаг обязательности свойства
     *
     * @return void
     */
    private function update(bool $required): void
    {
        $property = new Property(PromoBanner::iblockId());
        foreach (self::PROPERTIES as $propertyCode) {
            $property->addPropertyToQuery($propertyCode);
        }
        $propertiesInfo = $property->getPropertiesInfo();

        foreach ($propertiesInfo as $propertyInfo) {
            (new CIBlockProperty())->Update(
                $propertyInfo['PROPERTY_ID'],
                [
                    'IS_REQUIRED' => $required ? 'Y' : 'N'
                ]
            );
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(false);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '511',
            'CODE' => 'BUTTON_NAME_RU',
            'NAME' => 'Текст кнопки (рус)',
            'IBLOCK_ID' => PromoBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '512',
            'CODE' => 'BUTTON_NAME_EN',
            'NAME' => 'Текст кнопки (англ)',
            'IBLOCK_ID' => PromoBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '513',
            'CODE' => 'BUTTON_NAME_CN',
            'NAME' => 'Текст кнопки (кит)',
            'IBLOCK_ID' => PromoBanner::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '514',
            'CODE' => 'BUTTON_LINK',
            'NAME' => 'Ссылка кнопки',
            'IBLOCK_ID' => PromoBanner::iblockId()
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(true);

        $property = new Property(PromoBanner::iblockId());
        $property->addPropertyToQuery('BUTTON_NAME_RU');
        $property->addPropertyToQuery('BUTTON_NAME_EN');
        $property->addPropertyToQuery('BUTTON_NAME_CN');
        $property->addPropertyToQuery('BUTTON_LINK');
        $propertiesInfo = $property->getPropertiesInfo();

        foreach ($propertiesInfo as $propertyInfo) {
            CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
        }
    }
}
