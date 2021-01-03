<?php

use App\Models\Jewelry\HL\JewelryIndividualOrder;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Индивидуальный заказ ЮБИ"
 * Class AddJewelryIndividualOrderTable20200518102737550530
 */
class AddJewelryIndividualOrderTable20200518102737550530 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hlBlockId = (new HighloadBlock())
            ->constructDefault('JewelryIndividualOrder', JewelryIndividualOrder::TABLE_CODE)
            ->setLang('ru', 'Индивидуальный заказ ЮБИ')
            ->setLang('en', 'Jewelry Individual Order')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Имя заказчика')
            ->setLangDefault('en', 'Имя заказчика')
            ->setLangDefault('cn', 'Имя заказчика')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SURNAME')
            ->setXmlId('UF_SURNAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Фамилия заказчика')
            ->setLangDefault('en', 'Фамилия заказчика')
            ->setLangDefault('cn', 'Фамилия заказчика')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_PHONE')
            ->setXmlId('UF_PHONE')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Телефон заказчика')
            ->setLangDefault('en', 'Телефон заказчика')
            ->setLangDefault('cn', 'Телефон заказчика')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_EMAIL')
            ->setXmlId('UF_EMAIL')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Email заказчика')
            ->setLangDefault('en', 'Email заказчика')
            ->setLangDefault('cn', 'Email заказчика')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_COMMENT')
            ->setXmlId('UF_COMMENT')
            ->setLangDefault('ru', 'Комментарий заказчика')
            ->setLangDefault('en', 'Комментарий заказчика')
            ->setLangDefault('cn', 'Комментарий заказчика')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FILES')
            ->setXmlId('UF_FILES')
            ->setMultiple(true)
            ->setUserType('file')
            ->setLangDefault('ru', 'Прикрепленные файлы')
            ->setLangDefault('en', 'Прикрепленные файлы')
            ->setLangDefault('cn', 'Прикрепленные файлы')
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
        HighloadBlock::delete(JewelryIndividualOrder::TABLE_CODE);
    }
}
