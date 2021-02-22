<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\Dicts\JewelryStyle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Стиль" (ЮБИ)
 * Class UpdateJewelryStyleTable20200507112656916525
 */
class UpdateJewelryStyleTable20200507112656916525 extends BitrixMigration
{
    /** @var array|string[] Массив символьных кодов свойств */
    private const PROPERTIES = [
        'UF_GENDER',
        'UF_IMAGE'
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryStyle::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, 'UF_GENDER')
            ->setXmlId('UF_GENDER')
            ->setUserType('hlblock')
            ->setUserTypeHL(JewelrySex::TABLE_CODE, 'UF_NAME')
            ->setLangDefault('ru', 'Для кого')
            ->setLangDefault('en', 'Для кого')
            ->setLangDefault('cn', 'Для кого')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_IMAGE')
            ->setXmlId('UF_IMAGE')
            ->setUserType('file')
            ->setLangDefault('ru', 'Изображение')
            ->setLangDefault('en', 'Изображение')
            ->setLangDefault('cn', 'Изображение')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $properties = Property::getUserFields(JewelrySex::TABLE_CODE, self::PROPERTIES);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}
