<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\Company\Company;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания поля "Доменты, подтверждающий полномочия" в таблице "Компания"
 * Class AddAuthorityDocumentsFieldToCompany20200203134113429099
 */
class AddAuthorityDocumentsFieldToCompany20200203134113429099 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_AUTHORITY_DOCS';

    /** @var string $entityId Символьный код сущности для добавления свойства */
    private $entityId;

    /**
     * AddAuthorityDocumentsFieldToCompany20200203134113429099 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE)['ID'];
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
            ->setLangDefault('ru', 'Документы, подтверждающие полномочия')
            ->setLangDefault('en', 'Authority documents')
            ->setLangDefault('en', 'Authority documents')
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
            Company::TABLE_CODE,
            [self::PROPERTY_CODE]
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}
