<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class TranslatingToCn20190808165813086759 extends BitrixMigration
{
    /**
     * Массив китайский названий форм
     * @var array
     */
    private $shapeText = [
        'round' => '圆形',
        'emerald' => '祖母绿切割',
        'oval' => '椭圆形切割',
        'pear' => '梨形切割',
        'marquis' => '榄尖形切割',
        // 'fancy shape' => '',
        // 'square' => '',
        // 'triangle' => '',
        'princess' => '公主方形切割',
        'radiant' => '雷蒂恩形切割',
        //'trillion' => '',
        'cushion' => '枕形切割',
        'heart' => '心形切割',
        //'barion' => '',
        //'kite' => '',
        //'half moon' => '',
        //'shield' => '',
        //'small shield' => '',
        //'briolett' => '',
        'asscher' => '千福祖母绿切割',
        //'phoenix' => '',
        //'baguette' => '',
    ];

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->translateShape();
        $this->translatePromoBanner();
    }


    private function translateShape()
    {
        foreach ($this->shapeText as $code => $name_cn) {
            \App\Models\Catalog\HL\CatalogShape::getByExtID($code)
                ->update(['UF_DISPLAY_VALUE_CN' => $name_cn]);
        }
    }

    private function translatePromoBanner()
    {

        $pb = new \App\Models\Main\PromoBanner();

        $rows = \App\Models\Main\PromoBanner::getList();

        $row = $rows->first();


//Развитие социальной ответственности
        $row->fields['PROPERTY_TITLE_CN_VALUE'] = '领导社会活动';

        $row->fields['PROPERTY_DESCRIPTION_CN_VALUE'] = '我们每年花费超过1.5亿美元用于社会项目';



        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][0] = 'USD 8,482,992';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][1] = 'USD 7,282,202';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][2] = 'USD 18,149,328';

        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][0] = '2017年用于教育';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][1] = '2017年在艺术上花了很多钱';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][2] = '2017年用于公共需求';


        $row->fields['PROPERTY_TITLE_CN_VALUE'] = '发展社会责任';

        $row->fields['PROPERTY_DESCRIPTION_CN_VALUE'] = '我们每年向社会项目转移超过1.5亿美元。';

        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][0] = 'USD 8,482,992';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][1] = 'USD 7,282,202';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_VALUE'][2] = 'USD 18,149,328';

        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][0] = '2017年将8,482,992美元转入教育';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][1] = '2017年将7,282,202美元转入艺术';
        $row->fields['PROPERTY_INFOGRAPHICS_TEXTS_CN_DESCRIPTION'][2] = '2017年转入社会项目的18,149,328';


        $row->save();

    }

    private function untranslateShape()
    {
        foreach ($this->shapeText as $code => $name_cn) {
            \App\Models\Catalog\HL\CatalogShape::getByExtID($code)
                ->update(['UF_DISPLAY_VALUE_CN' => '']);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->untranslateShape();
    }
}
