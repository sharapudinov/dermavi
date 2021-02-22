<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\Auction;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для удаления свойства "Переопределить победителей"
 * Class RemoveRedefineWinnersPropertyFromAuctionIblock20200506133656398663
 */
class RemoveRedefineWinnersPropertyFromAuctionIblock20200506133656398663 extends BitrixMigration
{
    /** @var array|mixed[] $property Массив, описывающий настройки свойства */
    private $property;

    /**
     * RemoveRedefineWinnersPropertyFromAuctionIblock20200506133656398663 constructor.
     */
    public function __construct()
    {
        $this->property = [
            'NAME' => 'Переопределить победителей',
            'CODE' => 'REDEFINE_WINNERS',
            'SORT' => '525',
            'PROPERTY_TYPE' => 'L',
            'VALUES' => [
                ['VALUE' => 'Y']
            ],
            'IBLOCK_ID' => Auction::iblockId()
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
        $property = new Property(Auction::iblockId());
        $property->addPropertyToQuery($this->property['CODE']);
        $propertyInfo = $property->getPropertiesInfo()[$this->property['CODE']];
        CIBlockProperty::Delete($propertyInfo['PROPERTY_ID']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        (new CIBlockProperty())->Add($this->property);
    }
}
