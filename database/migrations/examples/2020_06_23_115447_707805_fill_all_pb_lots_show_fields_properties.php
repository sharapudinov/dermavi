<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionPBLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для заполнения свойств "Показывать чистоту" и "Показывать флуоресценцию" у лотов аукциона
 * Class FillAllPbLotsShowFieldsProperties20200623115447707805
 */
class FillAllPbLotsShowFieldsProperties20200623115447707805 extends BitrixMigration
{
    /** @var array|string[] $propertiesCodes - Массив, описывающий символьные коды новых свойств */
    private $propertiesCodes = [
        'SHOW_CLARITY',
        'SHOW_FLUORESCENCE'
    ];

    /** @var array|array[] $propertiesInfo - Массив, описывающий свойства в БД */
    private $propertiesInfo;

    /**
     * FillAllPbLotsShowFieldsProperties20200623115447707805 constructor.
     */
    public function __construct()
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(AuctionPBLot::iblockId());
        $property->addPropertyToQuery($this->propertiesCodes[0]);
        $property->addPropertyToQuery($this->propertiesCodes[1]);
        $this->propertiesInfo = $property->getPropertiesInfo();

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
        /** @var Collection|AuctionPBLot[] $auctionsLots - Коллекция лотов аукционов */
        $auctionsLots = AuctionPBLot::getList();
        foreach ($auctionsLots as $auctionLot) {
            CIBlockElement::SetPropertyValuesEx(
                $auctionLot->getId(),
                AuctionPBLot::iblockId(),
                [
                    $this->propertiesCodes[0] => $this->propertiesInfo[$this->propertiesCodes[0]]['VALUES']['Y'],
                    $this->propertiesCodes[1] => $this->propertiesInfo[$this->propertiesCodes[1]]['VALUES']['Y']
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
        /** @var Collection|AuctionPBLot[] $auctionsLots - Коллекция лотов аукционов */
        $auctionsLots = AuctionPBLot::getList();
        foreach ($auctionsLots as $auctionLot) {
            CIBlockElement::SetPropertyValuesEx(
                $auctionLot->getId(),
                AuctionPBLot::iblockId(),
                [
                    $this->propertiesCodes[0] => false,
                    $this->propertiesCodes[1] => false
                ]
            );
        }
    }
}
