<?php

use App\Models\Sale\PickupTime;
use Arrilot\BitrixCacher\Cache;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Class AddContentToIblockPickupTime20190429125517566879
 */
class AddContentToIblockPickupTime20190429125517566879 extends BitrixMigration
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
            PickupTime::create([
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
        /* @var Collection|PickupTime[] $pickupTimes*/
        $pickupTimes = PickupTime::filter(['NAME' => static::CONTENT])->getList();
        foreach ($pickupTimes as $pickupTime) {
            $pickupTime->delete();
        }
    }
}
