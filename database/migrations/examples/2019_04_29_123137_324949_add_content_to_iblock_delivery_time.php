<?php

use Arrilot\BitrixCacher\Cache;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use App\Models\Sale\DeliveryTime;
use Illuminate\Support\Collection;

/**
 * Class AddContentToIblockDeliveryTime20190429123137324949
 */
class AddContentToIblockDeliveryTime20190429123137324949 extends BitrixMigration
{
    /**
     * Варианты значений
     */
    const CONTENT = [
        '10:00 - 16:00',
        '16:00 - 18:00',
        '18:00 - 20:00',
    ];
    
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
        Cache::flush('arrilot_bih_iblock_id');
        foreach (static::CONTENT as $item) {
            DeliveryTime::create([
                'NAME' => $item
            ]);
        }
    }

    /**
     * Reverse the migration
     * @throws Exception
     */
    public function down()
    {
        /* @var Collection|DeliveryTime[] $deliveryTimes*/
        $deliveryTimes = DeliveryTime::filter(['NAME' => static::CONTENT])->getList();
        foreach ($deliveryTimes as $deliveryTime) {
            $deliveryTime->delete();
        }
    }
}
