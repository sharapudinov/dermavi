<?php

use App\Models\HL\PublicOfficial;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Публичное должностное лицо"
 * Class CreatePublicOfficialTable20191118150933716506
 */
class CreatePublicOfficialTable20191118150933716506 extends BitrixMigration
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
            ->constructDefault('PublicOfficial', PublicOfficial::TABLE_CODE)
            ->setLang('ru', 'Публичное должностное лицо')
            ->setLang('en', 'Public official')
            ->setLang('cn', 'Public official')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Имя')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_SURNAME')
            ->setXmlId('UF_SURNAME')
            ->setLangDefault('ru', 'Фамилия')
            ->setLangDefault('en', 'Surname')
            ->setLangDefault('cn', 'Surname')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_MIDDLE_NAME')
            ->setXmlId('UF_MIDDLE_NAME')
            ->setLangDefault('ru', 'Отчество')
            ->setLangDefault('en', 'Middle name')
            ->setLangDefault('cn', 'Middle name')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_POSITION')
            ->setXmlId('UF_POSITION')
            ->setLangDefault('ru', 'Должность')
            ->setLangDefault('en', 'Position')
            ->setLangDefault('cn', 'Position')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_RELATIVE_DEGREE')
            ->setXmlId('UF_RELATIVE_DEGREE')
            ->setLangDefault('ru', 'Степень родства')
            ->setLangDefault('en', 'Relative degree')
            ->setLangDefault('cn', 'Relative degree')
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
        HighloadBlock::delete(PublicOfficial::TABLE_CODE);
    }
}
