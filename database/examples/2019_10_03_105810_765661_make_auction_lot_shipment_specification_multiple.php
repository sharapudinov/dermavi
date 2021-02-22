<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Класс для описания миграции на изменение свойства "Спецификация отгрузки" ИБ "Лот аукциона" на множественное
 * Class MakeAuctionLotShipmentSpecificationMultiple20191003105810765661
 */
class MakeAuctionLotShipmentSpecificationMultiple20191003105810765661 extends BitrixMigration
{
    /** @var int $propertyId - Идентификатор свойства */
    private $propertyId;

    /** @var string $propertyCode - Символьный код свойства */
    private $propertyCode = 'SHIPMENT_SPECIFICATION';

    /**
     * MakeAuctionLotShipmentSpecificationMultiple20191003105810765661 constructor.
     */
    public function __construct()
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(AuctionLot::iblockId());
        $property->addPropertyToQuery($this->propertyCode);
        $this->propertyId = $property->getPropertiesInfo()[$this->propertyCode]['PROPERTY_ID'];

        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty)->Update($this->propertyId, [
            'MULTIPLE' => 'Y',
            'HINT' => 'Заполняться должно в том же порядке, в котором к лоту прикреплены бриллианты'
        ]);

        /** @var Collection|AuctionLot[] $auctionLots - Коллекция лотов аукционов */
        $auctionLots = AuctionLot::getList();
        foreach ($auctionLots as $auctionLot) {
            CIBlockElement::SetPropertyValuesEx(
                $auctionLot->getId(),
                AuctionLot::iblockId(),
                [
                    $this->propertyCode => false
                ]
            );
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
        (new CIBlockProperty)->Update($this->propertyId, [
            'MULTIPLE' => 'N',
            'HINT' => false
        ]);
    }
}
