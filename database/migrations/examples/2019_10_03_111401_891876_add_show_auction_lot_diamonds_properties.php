<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для создания свойств в ИБ "Лот аукциона" для показа свойств бриллиантов лота
 * Class AddShowAuctionLotDiamondsProperties20191003111401891876
 */
class AddShowAuctionLotDiamondsProperties20191003111401891876 extends BitrixMigration
{
    /** @var array|string[] $propertiesCodes - Массив, описывающий символьные коды новых свойств */
    private $propertiesCodes = [
        'SHOW_CLARITY',
        'SHOW_FLUORESCENCE'
    ];

    /** @var array|array[] $propertiesInfo - Массив, описывающий свойства в БД */
    private $propertiesInfo;

    /**
     * AddShowAuctionLotDiamondsProperties20191003111401891876 constructor.
     */
    public function __construct()
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(AuctionLot::iblockId());
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
        /** @var array|array $properties - Массив, описывающий новые свойства */
        $properties = [
            [
                'ACTIVE' => 'Y',
                'NAME' => 'Показывать чистоту',
                'CODE' => $this->propertiesCodes[0],
                'SORT' => '515',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'IBLOCK_ID' => AuctionLot::iblockId(),
                'VALUES' => [
                    ['VALUE' => 'Y', 'DEF' => 'Y']
                ]
            ],
            [
                'ACTIVE' => 'Y',
                'NAME' => 'Показывать флуоресценцию',
                'CODE' => $this->propertiesCodes[1],
                'SORT' => '516',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'IBLOCK_ID' => AuctionLot::iblockId(),
                'VALUES' => [
                    ['VALUE' => 'Y', 'DEF' => 'Y']
                ]
            ]
        ];

        foreach ($properties as $property) {
            (new CIBlockProperty())->Add($property);
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
        foreach ($this->propertiesInfo as $property) {
            CIBlockProperty::Delete($property['PROPERTY_ID']);
        }
    }
}
