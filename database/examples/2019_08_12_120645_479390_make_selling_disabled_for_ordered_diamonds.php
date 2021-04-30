<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auxiliary\Sale\BitrixOrder;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CIBlockElement;

/**
 * Миграция для деактивации купленных ранее товаров для продажи
 * Class MakeSellingDisabledForOrderedDiamonds20190812120645479390
 */
class MakeSellingDisabledForOrderedDiamonds20190812120645479390 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        CModule::IncludeModule('sale');

        $orders = BitrixOrder::getList();
        $products = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $products[] = $item->getProductId();
            }
        }

        $products = array_unique($products);

        /** @var \Illuminate\Support\Collection|Diamond[] $diamonds - Коллекция бриллиантов */
        $diamonds = Diamond::filter(['ID' => $products])->getList();
        foreach ($diamonds as $diamond) {
            CIBlockElement::SetPropertyValuesEx($diamond->getID(), Diamond::iblockID(), ['SELLING_AVAILABLE' => false]);
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
        CModule::IncludeModule('sale');

        $orders = BitrixOrder::getList();
        $products = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $products[] = $item->getProductId();
            }
        }

        $products = array_unique($products);

        /** @var \App\Core\BitrixProperty\Entity\Property $property - Сущность, описывающая битриксовое св-во */
        $property = Property::getListPropertyValue(
            Diamond::iblockID(),
            'SELLING_AVAILABLE',
            'Y'
        );

        /** @var \Illuminate\Support\Collection|Diamond[] $diamonds - Коллекция бриллиантов */
        $diamonds = Diamond::filter(['ID' => $products])->getList();
        foreach ($diamonds as $diamond) {
            $diamond->update([
                'PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
                'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => $property->getVariantId()
            ]);
        }
    }
}
