<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Beneficiary;
use App\Models\HL\Consignee;
use App\Models\HL\Signatory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "crm id" сущностям "грузополучатель", "подписант", "бенефициар"
 * Class AddCrmIdFieldIntoPersonalFormEntities20200319120334550627
 */
class AddCrmIdFieldIntoPersonalFormEntities20200319120334550627 extends BitrixMigration
{
    /** @var string Символьный код нового свойства */
    private const PROPERTY_CODE = 'UF_CRM_ID';

    /** @var array|string[] Массив символьных кодов таблиц для обновления */
    private const TABLES = [
        Signatory::TABLE_CODE,
        Consignee::TABLE_CODE,
        Beneficiary::TABLE_CODE
    ];

    /** @var array|string[] $entities Массив сущностей для обновления */
    private $entities;

    /**
     * AddCrmIdFieldIntoPersonalFormEntities20200319120334550627 constructor.
     */
    public function __construct()
    {
        foreach (self::TABLES as $table) {
            $this->entities[] = 'HLBLOCK_' . highloadblock($table)['ID'];
        }

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
        foreach ($this->entities as $entity) {
            (new UserField())->constructDefault($entity, self::PROPERTY_CODE)
                ->setXmlId(self::PROPERTY_CODE)
                ->setLangDefault('ru', 'CRM Id')
                ->setLangDefault('en', 'CRM Id')
                ->setLangDefault('cn', 'CRM Id')
                ->add();
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
        foreach ($this->entities as $key => $entity) {
            $property = Property::getUserFields(self::TABLES[$key], [self::PROPERTY_CODE])[0];
            UserField::delete($property['ID']);
        }
    }
}
