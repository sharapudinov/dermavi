<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\OrderPropsGroupTable;
use Bitrix\Sale\Internals\OrderPropsTable;

/**
 * Миграция для добавления свойств заказа.
 * Class AddOrderDeliveryProps20190206021334427228
 */
class AddOrderDeliveryProps20190206021334427228 extends BitrixMigration
{
    /** @var int Идентификатор типа юр лица */
    const PERSON_TYPE_LEGAL = 1;

    /** @var int Идентификатор типа физического лица */
    const PERSON_TYPE_PHYSICAL = 2;

    /** @var bool Использовать транзакцию */
    public $use_transaction = true;

    /**
     * AddOrderDeliveryProps20190206021334427228 constructor.
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        parent::__construct();

        Loader::includeModule('catalog');
        Loader::includeModule('sale');
    }

    /**
     * Run the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {
        $personTypes = [self::PERSON_TYPE_LEGAL, self::PERSON_TYPE_PHYSICAL];
        foreach ($personTypes as $typeId) {
            foreach ($this->getOrderProperties() as $group => $properties) {
                $groupId = $this->addPropertyGroup($group, $typeId);

                foreach ($properties as $property) {
                    $property['PERSON_TYPE_ID'] = $typeId;
                    $property['PROPS_GROUP_ID'] = $groupId;
                    $property['CODE'] = $group . '_' . $property['CODE'];

                    $this->addOrderProperty($property);
                }
            }
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     * @throws \Exception
     */
    public function down(): void
    {
        foreach ($this->getOrderProperties() as $groupName => $properties) {
            foreach ($properties as $property) {
                $this->deleteOrderProperty($groupName . '_' . $property['CODE']);
            }

            $rs = OrderPropsGroupTable::getList([
                'select' => ['ID'],
                'filter' => ['NAME' => $groupName]
            ]);

            while ($group = $rs->fetch()) {
                OrderPropsGroupTable::delete($group['ID']);
            }
        }
    }

    /**
     * Возвращает массив добавляемых свойств.
     * @return array
     */
    private function getOrderProperties(): array
    {
        return [
            'DELIVERY' => [
                ['CODE' => 'ZIP', 'NAME' => 'Почтовый индекс'],
                ['CODE' => 'COUNTRY', 'NAME' => 'Страна'],
                ['CODE' => 'COUNTRY_ID', 'NAME' => 'Идентификатор страны', 'TYPE' => 'NUMBER'],
                ['CODE' => 'REGION', 'NAME' => 'Штат/регион/провинция'],
                ['CODE' => 'CITY', 'NAME' => 'Город'],
                ['CODE' => 'STREET', 'NAME' => 'Улица'],
                ['CODE' => 'HOUSE', 'NAME' => 'Дом/корпус'],
                ['CODE' => 'FLAT', 'NAME' => 'Квартира/офис'],
                ['CODE' => 'FIRST_NAME', 'NAME' => 'Имя'],
                ['CODE' => 'LAST_NAME', 'NAME' => 'Фамилия'],
                ['CODE' => 'SECOND_NAME', 'NAME' => 'Отчество'],
                ['CODE' => 'BIRTHDAY', 'NAME' => 'Дата рождения'],
                ['CODE' => 'PHONE', 'NAME' => 'Телефон'],
                ['CODE' => 'DATE', 'NAME' => 'Дата доставки/самовывоза', 'TYPE' => 'DATE'],
                ['CODE' => 'TIME', 'NAME' => 'Время доставки/самовывоза'],
                ['CODE' => 'PICKUP_POINT', 'NAME' => 'Пункт самовывоза'],
                ['CODE' => 'PICKUP_POINT_ID', 'NAME' => 'Идентфикатор пункта самовывоза', 'TYPE' => 'NUMBER'],
            ],
            'BILLING' => [
                ['CODE' => 'ZIP', 'NAME' => 'Почтовый индекс'],
                ['CODE' => 'COUNTRY', 'NAME' => 'Страна'],
                ['CODE' => 'COUNTRY_ID', 'NAME' => 'Идентификатор страны', 'TYPE' => 'NUMBER'],
                ['CODE' => 'REGION', 'NAME' => 'Штат/регион/провинция'],
                ['CODE' => 'CITY', 'NAME' => 'Город'],
                ['CODE' => 'STREET', 'NAME' => 'Улица'],
                ['CODE' => 'HOUSE', 'NAME' => 'Дом/корпус'],
                ['CODE' => 'FLAT', 'NAME' => 'Квартира/офис'],
                ['CODE' => 'FIRST_NAME', 'NAME' => 'Имя'],
                ['CODE' => 'LAST_NAME', 'NAME' => 'Фамилия'],
                ['CODE' => 'SECOND_NAME', 'NAME' => 'Отчество'],
                ['CODE' => 'BIRTHDAY', 'NAME' => 'Дата рождения'],
                ['CODE' => 'PHONE', 'NAME' => 'Телефон'],
            ]
        ];
    }

    /**
     * Добавляет группу свойств заказа.
     * @param string $name
     * @param int $personTypeId
     * @return int
     * @throws MigrationException
     */
    private function addPropertyGroup(string $name, int $personTypeId): int
    {
        $res = OrderPropsGroupTable::add(['NAME' => $name, 'PERSON_TYPE_ID' => $personTypeId]);
        if (!$res->isSuccess()) {
            throw new MigrationException("Cannot add property group '{$name}'. ".implode("\n", $res->getErrorMessages()));
        }
        return $res->getId();
    }

    /**
     * Добавляет свойств заказа.
     * @param array $prop
     * @throws MigrationException
     */
    private function addOrderProperty(array $prop): void
    {
        $fields = [
            'TYPE' => 'STRING',
            'REQUIRED' => 'N',
            'USER_PROPS' => 'N',
            'UTIL' => 'N',
            'IS_FILTERED' => 'N',
            'ENTITY_REGISTRY_TYPE' => 'ORDER'
        ];

        $res = OrderPropsTable::add(array_merge($fields, $prop));
        if (!$res->isSuccess()) {
            throw new MigrationException("Cannot add property '{$prop['CODE']}'. ".implode("\n", $res->getErrorMessages()));
        }
    }

    /**
     * Удаляет свойств заказа по его коду.
     * @param string $code
     */
    private function deleteOrderProperty(string $code)
    {
        $rs = OrderPropsTable::getList([
            'filter' => ['CODE' => $code]
        ]);

        while ($prop = $rs->fetch()) {
            OrderPropsTable::delete($prop['ID']);
        }
    }
}
