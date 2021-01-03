<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\IBlock;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\UserField;

class CreateJeverlyTables20191216144620628667 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->createDict('JewelryMetal', 'app_jewelry_metal', 'Метал  (Для ЮИ)', 'Jewelry metal');
        $this->createDict('JewelryMetalColor', 'app_jewelry_metal_color', 'Цвет метала  (Для ЮИ)', 'Jewelry metal color');
        $this->createDict('JewelrySex', 'app_jewelry_sex', 'Пол  (Для ЮИ)', 'Jewelry Sex');
        $this->createDict('JewelryStyle', 'app_jewelry_style', 'Стиль  (Для ЮИ)', 'Jewelry style');
        $this->createDict('JewelryFineness', 'app_jewelry_fineness', 'Проба  (Для ЮИ)', 'Jewelry fineness');

        $this->createStoneTable();
        $this->createPolisherTable();
        $this->createDiamondsTable();
        $this->createJewelryTable();
    }

    public function createDict($name, $tableName, $ruDesc, $enDesc)
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault($name, $tableName)
            ->setLang('ru', $ruDesc)
            ->setLang('en', $enDesc)
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
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        Arrilot\BitrixMigrations\Constructors\IBlock::delete(\App\Models\Jewelry\Jewelry::iblockID());
        Arrilot\BitrixMigrations\Constructors\IBlock::delete(\App\Models\Jewelry\JewelrySku::iblockID());

        HighloadBlock::delete('app_jewelry_diamonds');
        HighloadBlock::delete('app_jewelry_polisher');
        HighloadBlock::delete('app_jewelry_stone');

        HighloadBlock::delete('app_jewelry_style');
        HighloadBlock::delete('app_jewelry_sex');
        HighloadBlock::delete('app_jewelry_metal_color');
        HighloadBlock::delete('app_jewelry_metal');
        HighloadBlock::delete('app_jewelry_fineness');
    }

    protected function createJewelryTable(): void
    {
        $factory = (new IBlock())
            ->constructDefault('Ювелирные изделия', 'app_jewelry', 'catalog')
            ->setVersion(2)
            ;

        $factory->fields['LID']      = ['s1', 's2', 's3'];
        $factory->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $factory->add();

        $entityId = Constructor::objIBlockSection($iblockId);

        //Свойства подраздела
        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->setLangDefault('cn', '俄文名字')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setLangDefault('ru', 'Наименование (англ)')
            ->setLangDefault('en', 'Name (en)')
            ->setLangDefault('cn', '英文名称')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_CN')
            ->setLangDefault('ru', 'Наименование (кит)')
            ->setLangDefault('en', 'Name (cn)')
            ->setLangDefault('cn', '中文名')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        //Свойства элемента
        (new IBlockProperty())->constructDefault('NAME_EN', 'Наименование (англ)', $iblockId)
            ->setIsRequired(true)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_RU', 'Наименование (рус)', $iblockId)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_CN', 'Наименование (кит)', $iblockId)
            ->add();

        $factory = (new IBlock())
            ->constructDefault('Торговые предложения ювелирных изделий', 'app_jewelry_sku', 'catalog')
            ->setVersion(2)
            ;

        $factory->fields['LID']      = ['s1', 's2', 's3'];
        $factory->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];

        $iblockId = $factory->add();

        (new IBlockProperty())->constructDefault('NAME_EN', 'Наименование (англ)', $iblockId)
            ->setIsRequired(true)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_RU', 'Наименование (рус)', $iblockId)
            ->add();

        (new IBlockProperty())->constructDefault('NAME_CN', 'Наименование (кит)', $iblockId)
            ->add();

        (new IBlockProperty())->constructDefault('METAL_ID', 'Метал', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_metal',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('METAL_COLOR_ID', 'Цвет метала', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_metal_color',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('SEX_ID', 'Пол', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_sex',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('STYLE_ID', 'Стиль', $iblockId)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_style',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('COLLECTION', 'Коллекция', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->add();

        (new IBlockProperty())->constructDefault('DIAMOND_IDS', 'Вставки', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setMultiple(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_diamonds',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('FINENESS_ID', 'Проба', $iblockId)
            ->setIsRequired(true)
            ->setFiltrable(true)
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => 'app_jewelry_fineness',
            ])
            ->add();

        (new IBlockProperty())->constructDefault('FINAL_PRICE', 'Цена готового изделия', $iblockId)
            ->setIsRequired(true)
            ->setPropertyType('N')
            ->add();

        (new IBlockProperty())->constructDefault('PRICE', 'Цена оправы', $iblockId)
            ->setPropertyType('N')
            ->add();

        (new IBlockProperty())->constructDefault('PHOTO_SMALL', 'Фото маленькое', $iblockId)
            ->setIsRequired(true)
            ->add();

        (new IBlockProperty())->constructDefault('PHOTO_BIG', 'Фото большое', $iblockId)
            ->setIsRequired(true)
            ->add();

        (new IBlockProperty())->constructDefault('VIDEOS', 'Видео', $iblockId)
            ->setMultiple(true)
            ->add();

        (new IBlockProperty())->constructDefault('WEIGHT', 'Вес оправы', $iblockId)
            ->setPropertyType('N')
            ->add();

        (new IBlockProperty())->constructDefault('FULL_WEIGHT', 'Вес украшения', $iblockId)
            ->setPropertyType('N')
            ->add();

        (new IBlockProperty())->constructDefault('SIZE', 'Размер', $iblockId)
            ->setPropertyType('N')
            ->add();
    }

    protected function createDiamondsTable(): void
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('JewelryDiamonds', 'app_jewelry_diamonds')
            ->setLang('ru', 'Бриллианты-вставки (для ЮИ)')
            ->setLang('en', 'Jewelry diamonds')
            ->add();

        $entityId = Constructor::objHLBlock($hblockId);

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setMandatory(true)
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

        (new UserField())->constructDefault($entityId, 'QUANTITY')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Кол-во камней')
            ->setLangDefault('en', 'Quantity')
            ->add();

        (new UserField())->constructDefault($entityId, 'FLUORESCENCE_ID')
            ->setLangDefault('ru', 'Флуорисценция')
            ->setLangDefault('en', 'Fluorescence')
            ->setUserTypeHL('catalog_fluorescence', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'POLISH_ID')
            ->setLangDefault('ru', 'Качество полировки')
            ->setLangDefault('en', 'Polish')
            ->setUserTypeHL('dict_polish', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'QUALITY_ID')
            ->setLangDefault('ru', 'Качество огранки')
            ->setLangDefault('en', 'Quality')
            ->setUserTypeHL('dict_quality', 'DISPLAY_VALUE_EN')
            ->add();

        (new UserField())->constructDefault($entityId, 'CULET_ID')
            ->setLangDefault('ru', 'Калетта')
            ->setLangDefault('en', 'Culet')
            ->setUserTypeHL('dict_culet', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'COLOR_ID')
            ->setLangDefault('ru', 'Цвет')
            ->setLangDefault('en', 'COLOR')
            ->setUserTypeHL('dict_color', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'SHAPE_ID')
            ->setLangDefault('ru', 'Форма')
            ->setLangDefault('en', 'Shape')
            ->setUserTypeHL('dict_from', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'CLARITY_ID')
            ->setLangDefault('ru', 'Чистота')
            ->setLangDefault('en', 'Clarity')
            ->setUserTypeHL('dict_qual', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'SYMMETRY_ID')
            ->setLangDefault('ru', 'Симметрия')
            ->setLangDefault('en', 'Symmetry')
            ->setUserTypeHL('dict_symmetry', 'NAME')
            ->add();

        (new UserField())->constructDefault($entityId, 'WEIGHT')
            ->setLangDefault('ru', 'Вес')
            ->setLangDefault('en', 'Weight')
            ->add();

        (new UserField())->constructDefault($entityId, 'DEPTH')
            ->setLangDefault('ru', 'Высота')
            ->setLangDefault('en', 'Depth')
            ->add();

        (new UserField())->constructDefault($entityId, 'TABLE')
            ->setLangDefault('ru', 'Площадка')
            ->setLangDefault('en', 'Table')
            ->add();

        (new UserField())->constructDefault($entityId, 'PRICE')
            ->setLangDefault('ru', 'Цена')
            ->setLangDefault('en', 'Price')
            ->add();

        (new UserField())->constructDefault($entityId, 'STONE_ID')
            ->setLangDefault('ru', 'Алмаз')
            ->setLangDefault('en', 'Stone')
            ->setUserTypeHL('app_jewelry_stone', 'NAME_RU')
            ->add();

        (new UserField())->constructDefault($entityId, 'POLISHER_ID')
            ->setLangDefault('ru', 'Огранщик')
            ->setLangDefault('en', 'Polisher')
            ->setUserTypeHL('app_jewelry_polisher', 'NAME_RU')
            ->add();

        (new UserField())->constructDefault($entityId, 'POLISH_DATE_START')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата начала обработки')
            ->setLangDefault('en', 'Polish date start')
            ->add();

        (new UserField())->constructDefault($entityId, 'POLISH_DATE_END')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата окончания обработки')
            ->setLangDefault('en', 'Polish date end')
            ->add();
    }

    protected function createStoneTable(): void
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('JewelryStone', 'app_jewelry_stone')
            ->setLang('ru', 'Алмазы (для ЮИ)')
            ->setLang('en', 'Jewelry stones')
            ->add();

        $entityId = Constructor::objHLBlock($hblockId);

        (new UserField())->constructDefault($entityId, 'XML_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Внешний идентификатор')
            ->setLangDefault('en', 'External ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setMandatory(true)
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

        (new UserField())->constructDefault($entityId, 'WEIGHT')
            ->setLangDefault('ru', 'Вес')
            ->setLangDefault('en', 'WEIGHT')
            ->add();

        (new UserField())->constructDefault($entityId, 'ORIGIN_DATE')
            ->setUserType('date')
            ->setLangDefault('ru', 'Дата добычи')
            ->setLangDefault('en', 'Origin date')
            ->add();

        (new UserField())->constructDefault($entityId, 'OWNER_ID')
            ->setLangDefault('ru', 'Собственник')
            ->setLangDefault('en', 'Owner')
            ->setUserTypeHL('dict_ownership', 'NAME')
            ->add();
    }

    protected function createPolisherTable(): void
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('JewelryPolisher', 'app_jewelry_polisher')
            ->setLang('ru', 'Полировщики (для ЮИ)')
            ->setLang('en', 'Jewelry diamonds polisher')
            ->add();

        $entityId = Constructor::objHLBlock($hblockId);

        (new UserField())->constructDefault($entityId, 'FIRSTNAME_EN')
            ->setLangDefault('ru', 'Имя (англ)')
            ->setLangDefault('en', 'Firstname (en)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'FIRSTNAME_RU')
            ->setLangDefault('ru', 'Имя (рус)')
            ->setLangDefault('en', 'Firstname (rus)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'FIRSTNAME_CN')
            ->setLangDefault('ru', 'Имя (кит)')
            ->setLangDefault('en', 'Firstname (cn)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'LASTNAME_EN')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Фамилия (англ)')
            ->setLangDefault('en', 'Lastname (en)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'LASTNAME_RU')
            ->setLangDefault('ru', 'Фамилия (рус)')
            ->setLangDefault('en', 'Lastname (rus)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'LASTNAME_CN')
            ->setLangDefault('ru', 'Фамилия (кит)')
            ->setLangDefault('en', 'Lastname (cn)')
            ->setSettings(['SIZE' => 100, 'ROWS' => 1])
            ->add();

        (new UserField())->constructDefault($entityId, 'EXPERIENCE')
            ->setLangDefault('ru', 'Год начала работы')
            ->setLangDefault('en', 'Experience')
            ->add();
    }
}
