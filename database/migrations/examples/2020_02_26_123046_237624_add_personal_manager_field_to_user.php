<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Персональный менеджер" пользователю
 * Class AddPersonalManagerFieldToUser20200226123046237624
 */
class AddPersonalManagerFieldToUser20200226123046237624 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = CUserTypeEntity::GetList([], [
            'ENTITY_ID' => 'USER',
            'FIELD_NAME' => 'UF_PERSONAL_MANAGER'
        ])->Fetch();

        if (!$property) {
            (new UserField())->constructDefault('USER', 'UF_PERSONAL_MANAGER')
                ->setUserType('integer')
                ->setLangDefault('ru', 'Персональный менеджер')
                ->setLangDefault('en', 'Personal manager')
                ->setLangDefault('cn', 'Personal manager')
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
        //
    }
}
