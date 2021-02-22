<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Beneficiary;
use App\Models\HL\Company\SingleExecutiveAuthority;
use App\Models\HL\Consignee;
use App\Models\HL\Signatory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "ИНН" в таблицы сущностей личной анкеты (Бенефициары, подписанты и тд)
 * Class AddTaxNumberFieldIntoPersonalFormEntitiesTables20200116103905143919
 */
class AddTaxNumberFieldIntoPersonalFormEntitiesTables20200116103905143919 extends BitrixMigration
{
    /** @var array|string[] Массив неймспейсов классов, которым надо добавить поле ИНН */
    private const ENTITIES = [
        SingleExecutiveAuthority::class
    ];

    /** @var string Символьный код свойства "ИНН" */
    private const PROPERTY_CODE = 'TAX_NUMBER';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        foreach (self::ENTITIES as $entity) {
            $entityId = 'HLBLOCK_' . highloadblock($entity::TABLE_CODE)['ID'];
            (new UserField())->constructDefault($entityId, self::PROPERTY_CODE)
                ->setXmlId(self::PROPERTY_CODE)
                ->setLangDefault('ru', 'ИНН')
                ->setLangDefault('en', 'Tax number')
                ->setLangDefault('cn', 'Tax number')
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
        foreach (self::ENTITIES as $entity) {
            $property = Property::getUserFields($entity::TABLE_CODE, [self::PROPERTY_CODE])[0];
            UserField::delete($property['ID']);
        }
    }
}
