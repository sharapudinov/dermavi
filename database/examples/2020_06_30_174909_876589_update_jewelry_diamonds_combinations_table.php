<?php

use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Бриллианты для заготовок"
 * Class UpdateJewelryDiamondsCombinationsTable20200630174909876589
 */
class UpdateJewelryDiamondsCombinationsTable20200630174909876589 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryBlankDiamonds::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, 'UF_PRICE')
            ->setXmlId('UF_PRICE')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Цена')
            ->setLangDefault('en', 'Цена')
            ->setLangDefault('cn', 'Цена')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_WEIGHT_FROM')
            ->setXmlId('UF_WEIGHT_FROM')
            ->setUserType('double')
            ->setSettings(['PRECISION' => 2])
            ->setLangDefault('ru', 'Вес (от)')
            ->setLangDefault('en', 'Вес (от)')
            ->setLangDefault('cn', 'Вес (от)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_WEIGHT_TO')
            ->setXmlId('UF_WEIGHT_TO')
            ->setUserType('double')
            ->setSettings(['PRECISION' => 2])
            ->setLangDefault('ru', 'Вес (до)')
            ->setLangDefault('en', 'Вес (до)')
            ->setLangDefault('cn', 'Вес (до)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_COLOR_SORT_FROM')
            ->setXmlId('UF_COLOR_SORT_FROM')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Цвет (от)')
            ->setLangDefault('en', 'Цвет (от)')
            ->setLangDefault('cn', 'Цвет (от)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_COLOR_SORT_TO')
            ->setXmlId('UF_COLOR_SORT_TO')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Цвет (до)')
            ->setLangDefault('en', 'Цвет (до)')
            ->setLangDefault('cn', 'Цвет (до)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_CLARITY_SORT_FROM')
            ->setXmlId('UF_CLARITY_SORT_FROM')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Чистота (от)')
            ->setLangDefault('en', 'Чистота (от)')
            ->setLangDefault('cn', 'Чистота (от)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_CLARITY_SORT_TO')
            ->setXmlId('UF_CLARITY_SORT_TO')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Чистота (до)')
            ->setLangDefault('en', 'Чистота (до)')
            ->setLangDefault('cn', 'Чистота (до)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_CUT_SORT_FROM')
            ->setXmlId('UF_CUT_SORT_FROM')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Огранка (от)')
            ->setLangDefault('en', 'Огранка (от)')
            ->setLangDefault('cn', 'Огранка (от)')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_CUT_SORT_TO')
            ->setXmlId('UF_CUT_SORT_TO')
            ->setUserType('integer')
            ->setLangDefault('ru', 'Огранка (до)')
            ->setLangDefault('en', 'Огранка (до)')
            ->setLangDefault('cn', 'Огранка (до)')
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
        //
    }
}
