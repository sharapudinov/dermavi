<?php

use App\Models\HL\UserRequestCrmChange;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "user_request_crm_change", описывающая пользователей,
 * по которым надо отправить обновленные профили в CRM
 * Class AddUserRequestCrmChangeTable20200318121900663549
 */
class AddUserRequestCrmChangeTable20200318121900663549 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('UserRequestCrmChange', UserRequestCrmChange::TABLE_CODE)
            ->setLang('ru', 'Пользователи для обновления профиля в CRM')
            ->setLang('en', 'Users to update CRM profile')
            ->add();

        $entityId = 'HLBLOCK_' . $hblockId;

        (new UserField())->constructDefault($entityId, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Идентификатор пользователя')
            ->setLangDefault('en', 'User id')
            ->setLangDefault('cn', 'User id')
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
        HighloadBlock::delete(UserRequestCrmChange::TABLE_CODE);
    }
}
