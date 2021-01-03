<?php

use App\Core\SprintOptions\OrderSettings;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Sale\Delivery\Services\Table;

/**
 * Миграция для регистрации служб доставки.
 * Class AddDeliveryServices20190206034046167029
 */
class AddDeliveryServices20190206034046167029 extends BitrixMigration
{
    /** @var bool Используем транзакцию */
    public $use_transaction = true;

    /**
     * AddDeliveryServices20190206034046167029 constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        parent::__construct();

        Loader::includeModule('sale');
    }

    /**
     * Run the migration.
     * @throws \Exception
     */
    public function up(): void
    {
        foreach ($this->getServices() as $fields) {
            $option = $fields['OPTION'];
            unset($fields['OPTION']);

            $res = Manager::add($fields);

            if (!$res->isSuccess()) {
                throw new MigrationException("Cannot add delivery service ".$fields['CODE']
                    . '. ' . implode("\n", $res->getErrorMessages()));
            }

            OrderSettings::setDbOption($option, $res->getId());
        }
    }

    /**
     * Reverse the migration.
     * @throws \Exception
     */
    public function down(): void
    {
        $ids = [];
        $names = array_map(function (array $fields) {
            return $fields['NAME'];
        }, $this->getServices());

        $rs = Table::getList();
        while ($service = $rs->fetch()) {
            if (in_array($service['NAME'], $names)) {
                $ids[] = $service['ID'];
            }
        }

        foreach ($ids as $serviceId) {
            Manager::delete($serviceId);
        }
    }

    /**
     * Возвращает данные для добавляемых служб доставки.
     * @return array
     */
    private function getServices(): array
    {
        return [
            [
                'CODE' => '',
                'PARENT_ID' => 0,
                'NAME' => 'Самовывоз',
                'ACTIVE' => 'Y',
                'DESCRIPTION' => 'Самовывоз',
                'SORT' => 100,
                'CURRENCY' => 'USD',
                'PRICE' => 0,
                'CLASS_NAME' => '\Bitrix\Sale\Delivery\Services\Configurable',
                'OPTION' => OrderSettings::OPTION_DELIVERY_PICKUP
            ],
            [
                'CODE' => '',
                'PARENT_ID' => 0,
                'NAME' => 'Бринкс',
                'ACTIVE' => 'Y',
                'DESCRIPTION' => 'Доставка через Бринкс',
                'SORT' => 200,
                'CURRENCY' => 'USD',
                'PRICE' => 0,
                'CLASS_NAME' => '\Bitrix\Sale\Delivery\Services\Configurable',
                'OPTION' => OrderSettings::OPTION_DELIVERY_BRINX
            ]
        ];
    }
}
