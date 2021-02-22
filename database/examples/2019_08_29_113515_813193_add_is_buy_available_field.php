<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Миграция для создания свойства пользователю "IsBuyAvailable", отвечающего за возможность участия в аукционах
 * Class AddIsBuyAvailableField20190829113515813193
 */
class AddIsBuyAvailableField20190829113515813193 extends BitrixMigration
{
    /** @var string $fieldCode - Символьный код поля */
    private $fieldCode = 'UF_CAN_BUY';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new UserField())->constructDefault('USER', $this->fieldCode)
            ->setXmlId($this->fieldCode)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Возможность участия в аукционах')
            ->setLangDefault('en', 'Can buy in auctions')
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
        $by = '';
        $order = '';
        $field[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => 'USER', 'FIELD_NAME' => $this->fieldCode]
        )->Fetch();
        UserField::delete($field['ID']);
    }
}
