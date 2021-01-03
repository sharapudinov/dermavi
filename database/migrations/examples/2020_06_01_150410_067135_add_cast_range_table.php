<?php

use App\Models\Jewelry\Dicts\JewelryDiamondSizesRange;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для создания таблицы "Диапазоны размеров бриллиантов"
 * Class AddCastRangeTable20200601150410067135
 */
class AddCastRangeTable20200601150410067135 extends BitrixMigration
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
            ->constructDefault('JewelryDiamondSizesRange', JewelryDiamondSizesRange::TABLE_CODE)
            ->setLang('ru', 'Диапазоны размеров бриллиантов')
            ->setLang('en', 'Jewelry Diamonds Sizes Ranges')
            ->setLang('cn', 'Jewelry Diamonds Sizes Ranges')
            ->add();

        $entityId = 'HLBLOCK_' . $hlBlockId;

        (new UserField())->constructDefault($entityId, 'UF_XML_ID')
            ->setXmlId('UF_XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_FROM')
            ->setXmlId('UF_FROM')
            ->setLangDefault('ru', 'Нижняя граница')
            ->setLangDefault('en', 'Нижняя граница')
            ->setLangDefault('cn', 'Нижняя граница')
            ->add();

        (new UserField())->constructDefault($entityId, 'UF_TO')
            ->setXmlId('UF_TO')
            ->setLangDefault('ru', 'Верхняя граница')
            ->setLangDefault('en', 'Верхняя граница')
            ->setLangDefault('cn', 'Верхняя граница')
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
        HighloadBlock::delete(JewelryDiamondSizesRange::TABLE_CODE);
    }
}
