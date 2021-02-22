<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Стиль (помощник по стилю)"
 * Class UpdateJewelryAssistantStyleTable20200630103932365577
 */
class UpdateJewelryAssistantStyleTable20200630103932365577 extends BitrixMigration
{
    /** @var string Символьный код свойства "изображение" */
    private const IMAGE = 'UF_IMAGE';

    /** @var string Символьный код свойства "текст" */
    private const TEXT = 'UF_TEXT';

    /** @var string $entity Символьный код сущности для обновления */
    private $entity;

    /**
     * UpdateJewelryAssistantStyleTable20200630103932365577 constructor.
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(JewelryAssistantStyle::TABLE_CODE)['ID'];
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
        $imageProperty = Property::getUserFields(JewelryAssistantStyle::TABLE_CODE, [self::IMAGE])[0];
        UserField::delete($imageProperty['ID']);

        (new UserField())->constructDefault($this->entity, self::IMAGE)
            ->setXmlId(self::IMAGE)
            ->setUserType('file')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Изображение')
            ->setLangDefault('en', 'Изображение')
            ->setLangDefault('cn', 'Изображение')
            ->add();

        (new UserField())->constructDefault($this->entity, self::TEXT)
            ->setXmlId(self::TEXT)
            ->setLangDefault('ru', 'Текст')
            ->setLangDefault('en', 'Текст')
            ->setLangDefault('cn', 'Текст')
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
        $properties = Property::getUserFields(JewelryAssistantStyle::TABLE_CODE, [self::IMAGE, self::TEXT]);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }

        (new UserField())->constructDefault($this->entity, self::IMAGE)
            ->setXmlId(self::IMAGE)
            ->setUserType('file')
            ->setLangDefault('ru', 'Изображение')
            ->setLangDefault('en', 'Изображение')
            ->setLangDefault('cn', 'Изображение')
            ->add();
    }
}
