<?php

use App\Models\HL\Company\CompanyCategory;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Категория юр лица"
 * Class AddIndividualCompanyCategory20191115181045763517
 */
class AddIndividualCompanyCategory20191115181045763517 extends BitrixMigration
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
            ->constructDefault('CompanyCategory', CompanyCategory::TABLE_CODE)
            ->setLang('ru', 'Категория юридического лица')
            ->setLang('en', 'Individual company category')
            ->setLang('cn', 'Individual company category')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_NAME')
            ->setXmlId('UF_NAME')
            ->setLangDefault('ru', 'Название')
            ->setLangDefault('en', 'Name')
            ->setLangDefault('cn', 'Name')
            ->add();

        CompanyCategory::create(['UF_NAME' => 'Органы государственной власти']);
        CompanyCategory::create(['UF_NAME' => 'Международная организация']);
        CompanyCategory::create(['UF_NAME' => 'Эмитент ценных бумаг']);
        CompanyCategory::create(['UF_NAME' => 'Иностранные структуры без образования юридического лица']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        HighloadBlock::delete(CompanyCategory::TABLE_CODE);
    }
}
