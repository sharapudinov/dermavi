<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\About\Office;
use App\Models\Sale\PickupPoint;
use Illuminate\Support\Collection;
use Arrilot\BitrixCacher\Cache;

/**
 * Class AddContentToIblockPickupPoints20190423130012210300
 */
class AddContentToIblockPickupPoints20190423130012210300 extends BitrixMigration
{
    /**
     * @var bool
     */
    public $use_transaction = true;
    
    /**
     * Run the migration
     * @throws Exception
     */
    public function up()
    {
        /*
         * Сбрасываем кеш, т.к. транзакция по созданию ИБ PickupPoint идет до этого,
         * и PickupPoint::iblockId() выдаст ошибку
         */
        Cache::flush('arrilot_bih_iblock_id');
        $offices = Office::baseQuery();
    
        $propertyUserEntityTypeItems = [];
        $propertyUserEntityTypeId = $this->getIblockPropIdByCode('USER_ENTITY_TYPE', PickupPoint::iblockId());
        $result = CIBlockPropertyEnum::GetList([], ['PROPERTY_ID' => $propertyUserEntityTypeId]);
        while ($propertyUserEntityTypeItem = $result->Fetch()) {
            $propertyUserEntityTypeItems[$propertyUserEntityTypeItem['XML_ID']] = $propertyUserEntityTypeItem;
        }
        
        foreach ($offices as $office) {
            PickupPoint::create([
                'NAME' => $office['NAME'],
                'PROPERTY_VALUES' => [
                    'NAME_RU' => $office['PROPERTY_NAME_RU_VALUE'],
                    'NAME_EN' => $office['PROPERTY_NAME_EN_VALUE'],
                    'NAME_CN' => $office['PROPERTY_NAME_CN_VALUE'],
                    'GOOGLE_MAP' => $office['PROPERTY_GOOGLE_MAP_VALUE'],
                    'CITY_RU' => $office['PROPERTY_CITY_RU_VALUE'],
                    'CITY_EN' => $office['PROPERTY_CITY_EN_VALUE'],
                    'CITY_CN' => $office['PROPERTY_CITY_CN_VALUE'],
                    'ADDRESS_RU' => $office['PROPERTY_ADDRESS_RU_VALUE'],
                    'ADDRESS_EN' => $office['PROPERTY_ADDRESS_EN_VALUE'],
                    'ADDRESS_CN' => $office['PROPERTY_ADDRESS_CN_VALUE'],
                    'PHONES' => $office['PROPERTY_PHONE_FOR_CLIENTS_VALUE'],
                    'WORKING_HOURS_EN' => '10:00–18:00',
                    'USER_ENTITY_TYPE' =>
                        $office['NAME'] == 'Moscow Office' ?
                            [$propertyUserEntityTypeItems['PHYSICAL_ENTITY']['ID'], $propertyUserEntityTypeItems['LEGAL_ENTITY']['ID']] :
                            [$propertyUserEntityTypeItems['LEGAL_ENTITY']['ID']]
                    ,
                ]
            ]);
        }
    }

    /**
     * Reverse the migration
     * @throws Exception
     */
    public function down()
    {
        $offices = Office::baseQuery();
        /* @var Collection|PickupPoint[] $pickupPoints*/
        $pickupPoints = PickupPoint::filter(['NAME' => $offices->pluck('NAME')->toArray()])->getList();
        foreach ($pickupPoints as $pickupPoint) {
            $pickupPoint->delete();
        }
    }
}
