<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryCast;
use App\Models\Jewelry\Dicts\JewelryDiamondSizesRange;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для изменения структуры таблицы "Каст ЮБИ"
 * Class UpdateJewelryCastTable20200601151015454721
 */
class UpdateJewelryCastTable20200601151015454721 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $properties = Property::getUserFields(JewelryCast::TABLE_CODE, ['UF_FROM', 'UF_TO']);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }

        $entity = 'HLBLOCK_' . highloadblock(JewelryCast::TABLE_CODE)['ID'];

        (new UserField())->constructDefault($entity, 'UF_RANGES')
            ->setXmlId('UF_RANGES')
            ->setMultiple(true)
            ->setShowInList(true)
            ->setUserType('directory')
            ->setUserTypeHL(JewelryDiamondSizesRange::TABLE_CODE, 'UF_XML_ID')
            ->setSettings(['LIST_HEIGHT' => 5])
            ->setLangDefault('ru', 'Диапазоны')
            ->setLangDefault('en', 'Диапазоны')
            ->setLangDefault('cn', 'Диапазоны')
            ->add();

        (new UserField())->constructDefault($entity, 'UF_ITEMS_COUNT')
            ->setXmlId('UF_ITEMS_COUNT')
            ->setLangDefault('ru', 'Количество изделий')
            ->setLangDefault('en', 'Количество изделий')
            ->setLangDefault('cn', 'Количество изделий')
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
        $properties = Property::getUserFields(JewelryCast::TABLE_CODE, ['UF_RANGE', 'UF_ITEMS_COUNT']);
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }
}
