<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для добавления поля "Доступен для продажи" в таблице "Бриллианты для заготовок"
 * Class AddCanBuyFieldToAppJewelryBlankDiamonds20200812175838261052
 */
class AddCanBuyFieldToAppJewelryBlankDiamonds20200812175838261052 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY = 'UF_SELLING_AVAILABLE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(JewelryBlankDiamonds::TABLE_CODE)['ID'];
        (new UserField())->constructDefault($entity, self::PROPERTY)
            ->setXmlId(self::PROPERTY)
            ->setUserType('boolean')
            ->setLangDefault('en', 'Доступен для продажи')
            ->setLangDefault('ru', 'Доступен для продажи')
            ->setLangDefault('cn', 'Доступен для продажи')
            ->add();

        /** @var Collection|JewelryBlankDiamonds[] $combinations */
        $combinations = JewelryBlankDiamonds::with('diamonds')->getList();
        foreach ($combinations as $combination) {
            $diamondsSelling = $combination->diamonds->pluck('PROPERTY_SELLING_AVAILABLE_VALUE');
            $sellingAvailable = true;
            $diamondsSelling->map(function (?string $diamondSellingAvailable) use (&$sellingAvailable) {
                if (!$diamondSellingAvailable) {
                    $sellingAvailable = false;
                }
            });

            $combination->update([self::PROPERTY => $sellingAvailable]);
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
        $property = Property::getUserFields(JewelryBlankDiamonds::TABLE_CODE, [self::PROPERTY])[0];
        UserField::delete($property['ID']);
    }
}
