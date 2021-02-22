<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\PersonalForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля
 * "Документально нотариально заверенные копии подтверждения указанных сведений" в таблицу "Личная анкета"
 * Class AddBeneficiaryDocumentsIntoPersonalFormTable20200204151043661899
 */
class AddBeneficiaryDocumentsIntoPersonalFormTable20200204151043661899 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_INFO_CONF';

    /** @var string $entityId Символьный код сущности для добавления свойства */
    private $entityId;

    /**
     * AddBeneficiaryDocumentsIntoPersonalFormTable20200204151043661899 constructor.
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
            ->setUserType('file')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Документально нотариально заверенные копии подтверждения указанных сведений')
            ->setLangDefault('en', 'Documentary notarized copies of confirmation of the specified information')
            ->setLangDefault('en', 'Documentary notarized copies of confirmation of the specified information')
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
