<?php

use App\Models\Jewelry\HL\JewelryConstructorConstructingItem;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Собираемое пользователем изделие"
 * Class AddJewelryConstructorConstructingItemTable20200805122604409562
 */
class AddJewelryConstructorConstructingItemTable20200805122604409562 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hlBlock = (new HighloadBlock())->constructDefault(
                'JewelryConstructorConstructingItem',
                JewelryConstructorConstructingItem::TABLE_CODE
            )->add();

        $entity = 'HLBLOCK_' . $hlBlock;

        (new UserField())->constructDefault($entity, 'UF_USER_ID')
            ->setXmlId('UF_USER_ID')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Id пользователя')
            ->setLangDefault('en', 'Id пользователя')
            ->setLangDefault('cn', 'Id пользователя')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_LINK')
            ->setXmlId('UF_LINK')
            ->setLangDefault('ru', 'Ссылка на последнее состояние')
            ->setLangDefault('en', 'Ссылка на последнее состояние')
            ->setLangDefault('cn', 'Ссылка на последнее состояние')
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
        HighloadBlock::delete(JewelryConstructorConstructingItem::TABLE_CODE);
    }
}