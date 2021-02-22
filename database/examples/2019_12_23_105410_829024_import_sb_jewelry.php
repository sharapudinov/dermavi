<?php

use App\Models\Jewelry\Dicts\JewelryFactory;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelryDiamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\UserField;

class ImportSbJewelry20191223105410829024 extends BitrixMigration
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
            ->constructDefault('JewelryFactory', 'app_jewelry_factory')
            ->setLang('ru', 'Производители ЮИ')
            ->setLang('en', 'Jewelry factory')
            ->add();

        $entityId = Constructor::objHLBlock($hblockId);

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Название (англ)')
            ->setLangDefault('en', 'Name (en)')
            ->setLangDefault('cn', '英文名称')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'SORT')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Сортировка')
            ->setLangDefault('en', 'Sort')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setLangDefault('ru', 'Наименование (англ)')
            ->setLangDefault('en', 'Name (en)')
            ->setLangDefault('cn', '英文名称')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->setLangDefault('cn', '俄文名字')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_CN')
            ->setLangDefault('ru', 'Наименование (кит)')
            ->setLangDefault('en', 'Name (cn)')
            ->setLangDefault('cn', '中文名')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();


        //Связь ИЮ - производитель
        $iblockId = Jewelry::iblockID();

        (new IBlockProperty())->constructDefault('FACTORY_ID', 'Откуда импортировано', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_factory',
            ])
            ->add();


        //Связь бриллиант-вставка ЮИ - производитель
        $entityId = Constructor::objHLBlock(\Arrilot\BitrixMigrations\Helpers::getHlId(JewelryDiamond::getTableName()));

        (new UserField())->constructDefault($entityId, 'FACTORY_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Откуда импортировано')
            ->setLangDefault('en', 'Jewelry import from')
            ->setUserTypeHL('app_jewelry_factory', 'NAME')
            ->add();

        JewelryFactory::create([
            'UF_NAME' => 'Кристалл',
            'UF_XML_ID'=> JewelryFactory::KRISTALL,
            'UF_NAME_RU' => 'Кристалл',
            'UF_NAME_EN' => 'Кристалл',
        ]);

        JewelryFactory::create([
            'UF_NAME' => 'ЮГ «СБ»',
            'UF_XML_ID' => JewelryFactory::UG_SB,
            'UF_NAME_RU' => 'ЮГ «СБ»',
            'UF_NAME_EN' => 'ЮГ «СБ»',
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        HighloadBlock::delete('app_jewelry_factory');
    }
}
