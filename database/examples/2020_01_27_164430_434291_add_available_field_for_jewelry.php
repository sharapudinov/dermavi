<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

class AddAvailableFieldForJewelry20200127164430434291 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        (new CIBlockProperty)->Add([
            'NAME'          => 'Доступен для продажи',
            'ACTIVE'        => 'Y',
            'CODE'          => 'SELLING_AVAILABLE',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE'     => 'C',
            'VALUES'        => [
                [
                    'VALUE' => 'Y',
                    'DEF'   => 'Y',
                ],
            ],
            'IBLOCK_ID'     => JewelrySku::iblockID(),
        ]);

        /** @var Collection|JewelrySku[] $jewelrySkus - Коллекция бриллиантов */
        $jewelrySkus = JewelrySku::getList();

        /** @var \App\Core\BitrixProperty\Entity\Property $property - Битриксовое свойство */
        $property = Property::getListPropertyValue(JewelrySku::iblockID(), 'SELLING_AVAILABLE', 'Y');

        foreach ($jewelrySkus as $jewelrySku) {
            $jewelrySku->update([
                'PROPERTY_SELLING_AVAILABLE_VALUE'   => 'Y',
                'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => $property->getVariantId(),
            ]);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function down()
    {
        $property = CIBlockProperty::GetList([], ['CODE' => 'SELLING_AVAILABLE', 'IBLOCK_ID' => JewelrySku::iblockID()])
            ->Fetch();
        CIBlockProperty::Delete($property['ID']);
    }
}
