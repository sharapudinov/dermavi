<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Diamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddAvailableForSellPropertyForDiamonds20190508121756935267 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty)->Add([
            'NAME' => 'Доступен для продажи',
            'ACTIVE' => 'Y',
            'CODE' => 'SELLING_AVAILABLE',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'VALUES' => [
                [
                    'VALUE' => 'Y',
                    'DEF' => 'Y'
                ]
            ],
            'IBLOCK_ID' => Diamond::iblockID()
        ]);

        /** @var \Illuminate\Support\Collection|Diamond[] $diamonds - Коллекция бриллиантов */
        $diamonds = Diamond::getList();

        /** @var \App\Core\BitrixProperty\Entity\Property $property - Битриксовое свойство */
        $property = Property::getListPropertyValue(Diamond::iblockID(), 'SELLING_AVAILABLE', 'Y');

        foreach ($diamonds as $diamond) {
            $diamond->update([
                'PROPERTY_SELLING_AVAILABLE_VALUE' => 'Y',
                'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => $property->getVariantId()
            ]);
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
        $property = CIBlockProperty::GetList([], ['CODE' => 'SELLING_AVAILABLE', 'IBLOCK_ID' => Diamond::iblockID()])
            ->Fetch();
        CIBlockProperty::Delete($property['ID']);
    }
}
