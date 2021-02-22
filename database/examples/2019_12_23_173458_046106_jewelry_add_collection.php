<?php

use App\Models\Jewelry\Dicts\JewelryFactory;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelryDiamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\UserField;
class JewelryAddCollection20191223173458046106 extends BitrixMigration
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
            ->constructDefault('JewelryCollection', 'app_jewelry_collection')
            ->setLang('ru', 'Коллекции ЮИ')
            ->setLang('en', 'Jewelry collection')
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

        //Связь ИЮ - коллекция
        $iblockId = Jewelry::iblockID();

        (new IBlockProperty())->constructDefault('COLLECTION_ID', 'Коллекция', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_collection',
            ])
            ->add();
        
        $this->deleteIblockElementPropertyByCode(\App\Models\Jewelry\JewelrySku::iblockID(),'COLLECTION');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        HighloadBlock::delete('app_jewelry_collection');
        $this->deleteIblockElementPropertyByCode(\App\Models\Jewelry\Jewelry::iblockID(), 'COLLECTION_ID');

        (new IBlockProperty())->constructDefault('COLLECTION', 'Коллекция', \App\Models\Jewelry\JewelrySku::iblockID())
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->add();
    }
}
