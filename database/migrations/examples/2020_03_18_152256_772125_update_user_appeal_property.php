<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\SalutationType;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления свойства "Обращение" у пользователя
 * Class UpdateUserAppealProperty20200318152256772125
 */
class UpdateUserAppealProperty20200318152256772125 extends BitrixMigration
{
    /** @var string Символьный код сущности */
    private const ENTITY_ID = 'USER';

    /** @var string Символьный код свойства для обновления */
    private const PROPERTY_CODE = 'UF_APPEAL';

    /**
     * UpdateUserAppealProperty20200318152256772125 constructor.
     */
    public function __construct()
    {
        $propertyId = Property::getPropertiesInfoFromUser([self::PROPERTY_CODE])[self::PROPERTY_CODE]['PROPERTY_ID'];
        UserField::delete($propertyId);

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
        (new UserField())->constructDefault(self::ENTITY_ID, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setUserTypeHL(SalutationType::TABLE_CODE, 'UF_VALUE')
            ->setLangDefault('ru', 'Обращение')
            ->setLangDefault('en', 'Salutation')
            ->setLangDefault('cn', 'Salutation')
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
        (new UserField())->constructDefault(self::ENTITY_ID, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setUserType('string')
            ->setLangDefault('ru', 'Обращение (mr, ms, miss)')
            ->setLangDefault('en', 'Обращение (mr, ms, miss)')
            ->setLangDefault('cn', 'Обращение (mr, ms, miss)')
            ->add();
    }
}
