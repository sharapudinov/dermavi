<?php

use App\Core\BitrixProperty\Property;
use App\Models\Blog\GridElement;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для добавления свойств в ИБ "Элементы грида"
 * Class AddPropertiesToGridElement20200416160011603932
 */
class AddPropertiesToGridElement20200416160011603932 extends BitrixMigration
{
    /** @var array|string[] Массив свойств для добавления */
    private const PROPERTIES = [
        'BUTTON_TEXT_EN' => 'Кнопка (текст англ)',
        'BUTTON_TEXT_CN' => 'Кнопка (текст кит)',
        'BUTTON_URL_EN' => 'Кнопка (ссылка англ)',
        'BUTTON_URL_CN' => 'Кнопка (ссылка кит)'
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = new Property(GridElement::iblockID());
        $property->addPropertyToQuery('BUTTON_TEXT');
        $property->addPropertyToQuery('BUTTON_URL');
        $properties = $property->getPropertiesInfo();

        (new CIBlockProperty())->Update(
            $properties['BUTTON_TEXT']['PROPERTY_ID'],
            [
                'NAME' => 'Кнопка (текст рус)',
                'CODE' => 'BUTTON_TEXT_RU'
            ]
        );

        (new CIBlockProperty())->Update(
            $properties['BUTTON_URL']['PROPERTY_ID'],
            [
                'NAME' => 'Кнопка (ссылка рус)',
                'CODE' => 'BUTTON_URL_RU'
            ]
        );

        foreach (self::PROPERTIES as $code => $name) {
            (new CIBlockProperty())->Add([
                'ACTIVE' => 'Y',
                'CODE' => $code,
                'NAME' => $name,
                'IBLOCK_ID' => GridElement::iblockID()
            ]);
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
        $property = new Property(GridElement::iblockID());
        $property->addPropertyToQuery('BUTTON_TEXT_RU');
        $property->addPropertyToQuery('BUTTON_URL_RU');

        foreach (self::PROPERTIES as $key => $name) {
            $property->addPropertyToQuery($key);
        }

        $properties = $property->getPropertiesInfo();

        (new CIBlockProperty())->Update(
            $properties['BUTTON_TEXT_RU']['PROPERTY_ID'],
            [
                'NAME' => 'Кнопка (текст)',
                'CODE' => 'BUTTON_TEXT'
            ]
        );

        (new CIBlockProperty())->Update(
            $properties['BUTTON_URL_RU']['PROPERTY_ID'],
            [
                'NAME' => 'Кнопка (ссылка)',
                'CODE' => 'BUTTON_URL'
            ]
        );

        foreach ($properties as $code => $property) {
            if (in_array($code, self::PROPERTIES)) {
                CIBlockProperty::Delete($property['PROPERTY_ID']);
            }
        }
    }
}
