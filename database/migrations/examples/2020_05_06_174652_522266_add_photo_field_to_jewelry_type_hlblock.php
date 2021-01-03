<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления свойство "Изображение" в хлблок "Тип изделия"
 * Class AddPhotoFieldToJewelryTypeHlblock20200506174652522266
 */
class AddPhotoFieldToJewelryTypeHlblock20200506174652522266 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_IMAGE';

    /** @var string $entity Символьный код сущности для обновления */
    private $entity;

    /**
     * AddPhotoFieldToJewelryTypeHlblock20200506174652522266 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(JewelryType::TABLE_CODE)['ID'];
        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new UserField())->constructDefault($this->entity, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
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
        $property = Property::getUserFields(JewelryType::TABLE_CODE, [self::PROPERTY_CODE])[0];
        UserField::delete($property['ID']);
    }
}
