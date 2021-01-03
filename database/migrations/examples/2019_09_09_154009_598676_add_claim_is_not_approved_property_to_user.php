<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Миграция для создания свойства "Заявка в процессе согласования" для пользователя
 * Class AddClaimIsNotApprovedPropertyToUser20190909154009598676
 */
class AddClaimIsNotApprovedPropertyToUser20190909154009598676 extends BitrixMigration
{
    /** @var string $propertyCode - Символьный код свойства */
    private $propertyCode = 'UF_IS_APPROVING';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new UserField())->constructDefault('USER', $this->propertyCode)
            ->setXmlId($this->propertyCode)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Заявка в процессе согласования')
            ->setLangDefault('en', 'Claim is approving')
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
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $this->propertyCode]
        )->Fetch();
        UserField::delete($property['ID']);
    }
}
