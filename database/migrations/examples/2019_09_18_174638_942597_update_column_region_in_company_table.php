<?php

use App\Models\HL\Company\Company;
use App\Models\HL\DeliveryAddress;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Миграция для обновления свойства "Регион" в таблице "Компания"
 * Class UpdateColumnRegionInCompanyTable20190918174638942597
 */
class UpdateColumnRegionInCompanyTable20190918174638942597 extends BitrixMigration
{
    /** @var string $propertySymCode - Символьный код свойства */
    private $propertySymCode = 'UF_REGION';

    /** @var string $highloadBlockId - Идентификатор хлблока */
    private $highloadBlockId;

    /**
     * UpdateRegionColumnInDeliveryAddressesTable20190918164641624317 constructor.
     */
    public function __construct()
    {
        $this->highloadBlockId = 'HLBLOCK_' . highloadblock(Company::TABLE_CODE)['ID'];

        $by = '';
        $order = '';
        $field = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => $this->highloadBlockId, 'FIELD_NAME' => $this->propertySymCode]
        )->Fetch();
        UserField::delete($field['ID']);

        parent::__construct();
    }

    /**
     * Создает свойство
     *
     * @param string $fieldType
     *
     * @return void
     *
     * @throws Exception
     */
    private function addProperty(string $fieldType): void
    {
        (new UserField())->constructDefault($this->highloadBlockId, $this->propertySymCode)
            ->setXmlId($this->propertySymCode)
            ->setUserType($fieldType)
            ->setLangDefault('ru', 'Регион')
            ->setLangDefault('en', 'Region')
            ->setLangDefault('cn', 'Region')
            ->add();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->addProperty('integer');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->addProperty('string');
    }
}
