<?php

use App\Models\Catalog\HL\PaidServiceCategory;
use App\Models\Catalog\PaidService;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Catalog\GroupTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Loader;
use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Model\Product;

class AddEngravingJewelryService20200119155842712909 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        Loader::includeModule("catalog");

        $legalPrice = GroupTable::getList([
            'filter' => [
                'XML_ID' => 'SALE_COST',
            ],
        ])->fetch();

        $row = PaidService::create([
            'NAME'            => 'Гравировка ювелирного изделия',
            'PROPERTY_VALUES' => [
                'NAME_RU'       => 'Гравировка',
                'NAME_EN'       => 'Engraving',
                'NAME_CN'       => '',
                'CATEGORY'      => 'engraving',
                'MAXIMUM_COUNT' => 50,
                'FOR_FREE_FROM' => '100000|RUB',
                'WEIGHT_TO'     => 100000,
            ],
            'CODE'            => 'engraving-jewelry',
            'XML_ID'          => 'engraving-jewelry',
        ]);

        $this->initProduct($row->getId());

        Price::add([
            "PRODUCT_ID"       => $row->getId(),
            "CATALOG_GROUP_ID" => $legalPrice['ID'],
            "PRICE"            => "40",
            "CURRENCY"         => "RUB",
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
        PaidService::getByCode('engraving-jewelry')->delete();
    }

    private function initProduct($id)
    {
        $product = ProductTable::getById($id)->fetch();
        if ($product) {
            return;
        }

        Product::add([
            'fields' => [
                'ID' => $id,
            ],
        ]);
    }
}
