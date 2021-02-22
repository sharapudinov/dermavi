<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\AuctionLot;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления свойства "Переторжка" для ИБ "Лот аукциона"
 * Class UpdateAuctionLotRebiddingProperty20191004104650388773
 */
class UpdateAuctionLotRebiddingProperty20191004104650388773 extends BitrixMigration
{
    /** @var string $isRebiddingPropertyCode - Символьный код свойства "Переторжка" */
    private $isRebiddingPropertyCode = 'IS_REBIDDING';

    /** @var array|mixed[] $hadRebiddingProperty - Массив, описывающий свойство "Была переторжка" */
    private $hadRebiddingProperty;

    /**
     * UpdateAuctionLotRebiddingProperty20191004104650388773 constructor.
     */
    public function __construct()
    {
        $this->hadRebiddingProperty = [
            'ACTIVE' => 'Y',
            'NAME' => 'Была переторжка',
            'CODE' => 'HAD_REBIDDING',
            'IBLOCK_ID' => AuctionLot::iblockId()
        ];

        parent::__construct();
    }

    /**
     * Возвращает массив, описывающий свойства
     *
     * @param string|null $newProperty - Новое свойство
     *
     * @return array|array[]
     */
    private function getProperties(string $newProperty = null): array
    {
        /** @var Property $property - Экземпляр класса для работы со свойствами */
        $property = new Property(AuctionLot::iblockId());
        $property->addPropertyToQuery($this->isRebiddingPropertyCode);
        if ($newProperty) {
            $property->addPropertyToQuery($newProperty);
        }

        return $property->getPropertiesInfo();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlockProperty())->Update($this->getProperties()[$this->isRebiddingPropertyCode]['PROPERTY_ID'], [
            'NAME' => 'Активная переторжка'
        ]);

        (new CIBlockProperty())->Add($this->hadRebiddingProperty);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** @var array|array[] $properties - Массив, описывающий свойства */
        $properties = $this->getProperties($this->hadRebiddingProperty['CODE']);

        (new CIBlockProperty())->Update($properties[$this->isRebiddingPropertyCode]['PROPERTY_ID'], [
            'NAME' => 'Переторжка'
        ]);

        CIBlockProperty::Delete($properties[$this->hadRebiddingProperty['CODE']]['PROPERTY_ID']);
    }
}
