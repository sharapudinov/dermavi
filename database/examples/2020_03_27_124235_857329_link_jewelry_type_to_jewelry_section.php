<?php

use App\Models\Jewelry\Dicts\JewelryType;
use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для привязывания таблицы "Тип ювелирного изделия" к разделам ИБ "Ювелирные изделия"
 * Class LinkJewelryTypeToJewelrySection20200327124235857329
 */
class LinkJewelryTypeToJewelrySection20200327124235857329 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_TYPES';

    /** @var string $entityId Символьный код сущности */
    private $entityId;

    /**
     * LinkJewelryTypeToJewelrySection20200327124235857329 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'IBLOCK_' . Jewelry::iblockID() . '_SECTION';
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
        (new UserField())->constructDefault($this->entityId, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setMultiple(true)
            ->setUserType('directory')
            ->setUserTypeHL(JewelryType::TABLE_CODE, 'UF_XML_ID')
            ->setSettings(['LIST_HEIGHT' => 5])
            ->setLangDefault('ru', 'Типы изделий')
            ->setLangDefault('en', 'Jewelry types')
            ->setLangDefault('cn', 'Jewelry types')
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
        $property = CUserTypeEntity::GetList(
            [],
            ['ENTITY_ID' => $this->entityId, 'FIELD_NAME' => self::PROPERTY_CODE]
        )->Fetch();
        UserField::delete($property['ID']);
    }
}
