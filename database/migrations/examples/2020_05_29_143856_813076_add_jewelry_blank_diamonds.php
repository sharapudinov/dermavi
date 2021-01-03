<?php

use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Бриллианты для заготовок"
 * Class AddJewelryBlankDiamonds20200529143856813076
 */
class AddJewelryBlankDiamonds20200529143856813076 extends BitrixMigration
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
            ->constructDefault('JewelryBlankDiamonds', JewelryBlankDiamonds::TABLE_CODE)
            ->setLang('ru', 'Бриллианты для заготовок')
            ->setLang('en', 'Jewelry blank diamonds')
            ->setLang('cn', 'Jewelry blank diamonds')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;
        (new UserField())->constructDefault($entityId, 'UF_BLANK_ID')
            ->setXmlId('UF_BLANK_ID')
            ->setLangDefault('ru', 'Идентификатор заготовки')
            ->setLangDefault('en', 'Blank id')
            ->setLangDefault('cn', 'Blank id')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_DIAMOND_ID')
            ->setXmlId('UF_DIAMOND_ID')
            ->setLangDefault('ru', 'Идентификатор бриллианта')
            ->setLangDefault('en', 'diamond id')
            ->setLangDefault('cn', 'diamond id')
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
        HighloadBlock::delete(JewelryBlankDiamonds::TABLE_CODE);
    }
}
