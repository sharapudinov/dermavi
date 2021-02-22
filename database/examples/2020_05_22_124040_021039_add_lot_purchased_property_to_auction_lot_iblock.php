<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания свойства "Лот выкуплен" в ИБ "Лот аукциона"
 * Class AddLotPurchasedPropertyToAuctionLotIblock20200522124040021039
 */
class AddLotPurchasedPropertyToAuctionLotIblock20200522124040021039 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'PURCHASED';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add([
            'ACTIVE' => 'Y',
            'SORT' => '518',
            'CODE' => self::PROPERTY_CODE,
            'NAME' => 'Лот выкуплен',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'IBLOCK_ID' => AuctionLot::iblockId(),
            'VALUES' => [
                ['VALUE' => 'Y']
            ]
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
        $property = new Property(AuctionLot::iblockId());
        $property->addPropertyToQuery(self::PROPERTY_CODE);
        $property = $property->getPropertiesInfo()[self::PROPERTY_CODE];
        CIBlockProperty::Delete($property['PROPERTY_ID']);
    }
}
