<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PersonalForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания свойства "Анкета проверена" для таблицы "Личная анкета"
 * Class AddPersonalFormApprovedProperty20200207163517151824
 */
class AddPersonalFormApprovedProperty20200207163517151824 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_APPROVED';

    /** @var string $entityId Идентификатор сущности */
    private $entityId;

    /**
     * AddPersonalFormApprovedProperty20200207163517151824 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(PersonalForm::TABLE_CODE)['ID'];
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
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Анкета проверена')
            ->setLangDefault('en', 'Form approved')
            ->setLangDefault('cn', 'Form approved')
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
        $properties = Property::getUserFields(
            PersonalForm::TABLE_CODE,
            [self::PROPERTY_CODE]
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}
