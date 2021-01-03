<?php

use App\Core\BitrixProperty\Property;
use App\Models\Auctions\Auction;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Миграция для изменения свойства "Менеджер аукциона" на мультиязычное
 * Class MakeAuctionManagerFieldMultilanguage20190904171020461950
 */
class MakeAuctionManagerFieldMultilanguage20190904171020461950 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var Property $property - Экземпляр класса для работы с битриксовыми свойствами */
        $property = new Property(Auction::iblockId());
        $property->addPropertyToQuery('AUCTION_MANAGER');

        (new CIBlockProperty())->Update($property->getPropertiesInfo()['AUCTION_MANAGER']['PROPERTY_ID'], [
            'CODE' => 'AUCTION_MANAGER_RU',
            'NAME' => 'Менеджер аукциона (рус)',
            'SORT' => '495',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IBLOCK_ID' => Auction::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Менеджер аукциона (англ)',
            'CODE' => 'AUCTION_MANAGER_EN',
            'SORT' => '496',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IBLOCK_ID' => Auction::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Менеджер аукциона (кит)',
            'CODE' => 'AUCTION_MANAGER_CN',
            'SORT' => '497',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IBLOCK_ID' => Auction::iblockId()
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
        /** @var Property $property - Экземпляр класса для работы с битриксовыми свойствами */
        $property = new Property(Auction::iblockId());
        $property->addPropertyToQuery('AUCTION_MANAGER_RU');
        $property->addPropertyToQuery('AUCTION_MANAGER_EN');
        $property->addPropertyToQuery('AUCTION_MANAGER_CN');

        /** @var array|array[] $propertiesInfo - Массив, описывающий свойства */
        $propertiesInfo = $property->getPropertiesInfo();

        CIBlockProperty::Delete($propertiesInfo['AUCTION_MANAGER_EN']['PROPERTY_ID']);
        CIBlockProperty::Delete($propertiesInfo['AUCTION_MANAGER_CN']['PROPERTY_ID']);

        (new CIBlockProperty())->Update($propertiesInfo['AUCTION_MANAGER_RU']['PROPERTY_ID'], [
            'CODE' => 'AUCTION_MANAGER',
            'NAME' => 'Менеджер аукциона',
            'SORT' => '497',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IBLOCK_ID' => Auction::iblockId()
        ]);
    }
}
