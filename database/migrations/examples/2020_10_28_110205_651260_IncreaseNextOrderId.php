<?php

use App\Core\Sale\OrderCreator;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;

Loader::includeModule('sale');
class IncreaseNextOrderId20201028110205651260 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $res = \Bitrix\Sale\Order::getList(['order' => ['ID' => 'DESC'], 'limit' => 1, 'select' => ['ID']]);
        if ($order = $res->fetch()) {
            $orderId = $order['ID'];
            OrderCreator::incNextOrderId($orderId, 10000);
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
        //
    }
}
