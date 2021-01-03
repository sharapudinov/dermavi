<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания поля "Ставка при переторжке"
 * Class AddRebiddingBidIntoAuctionLotTable20191108151722331314
 */
class AddRebiddingBidIntoAuctionLotTable20191108151722331314 extends BitrixMigration
{
    /** @var array|mixed[] $property - Массив, описывающий свойство */
    private $property;

    /**
     * AddRebiddingBidIntoAuctionLotTable20191108151722331314 constructor.
     */
    public function __construct()
    {
        $this->property = [
            'SORT' => '517',
            'CODE' => 'REBIDDING_BID',
            'NAME' => 'Ставка при переторжке',
            'IBLOCK_ID' => AuctionLot::iblockId()
        ];
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Add($this->property);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(AuctionLot::iblockId());
        $property->addPropertyToQuery($this->property['CODE']);
        CIBlockProperty::Delete($property[$this->property['CODE']]['PROPERTY_ID']);
    }
}
