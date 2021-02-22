<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\OrderPropsGroupTable;
use Bitrix\Sale\Internals\OrderPropsTable;

class AddEmailToOrder20200129144537912819 extends BitrixMigration
{
    /** @var int Идентификатор типа физического лица */
    const PERSON_TYPE_PHYSICAL = 2;

    /** @var bool Использовать транзакцию */
    public $use_transaction = true;

    /**
     * AddOrderDeliveryProps20190206021334427228 constructor.
     *
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
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {

        $this->addOrderProperty([
            'PERSON_TYPE_ID' => self::PERSON_TYPE_PHYSICAL,
            'PROPS_GROUP_ID' => $this->getPropertyGroup('Свойства заказа', self::PERSON_TYPE_PHYSICAL),
            'CODE'           => 'EMAIL',
            'NAME'           => 'Email',
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
        $this->deleteOrderProperty('EMAIL', self::PERSON_TYPE_PHYSICAL);
    }

    /**
     * Удаляет свойств заказа по его коду.
     *
     * @param string $code
     * @param        $typeId
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function deleteOrderProperty(string $code, $typeId)
    {
        $rs = OrderPropsTable::getList([
            'filter' => ['CODE' => $code, 'PERSON_TYPE_ID' => $typeId],
        ]);

        while ($prop = $rs->fetch()) {
            OrderPropsTable::delete($prop['ID']);
        }
    }

    /**
     * Добавляет группу свойств заказа.
     *
     * @param string $name
     * @param int    $personTypeId
     *
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getPropertyGroup(string $name, int $personTypeId): int
    {
        $rows = OrderPropsGroupTable::getList([
                'filter' => [
                    ['NAME' => $name, 'PERSON_TYPE_ID' => $personTypeId],
                ],
            ]
        );

        $row = $rows->fetch();

        return $row['ID'];
    }

    /**
     * Добавляет свойств заказа.
     *
     * @param array $prop
     *
     * @throws MigrationException
     */
    private function addOrderProperty(array $prop): void
    {
        $fields = [
            'TYPE'                 => 'STRING',
            'REQUIRED'             => 'N',
            'USER_PROPS'           => 'N',
            'UTIL'                 => 'N',
            'IS_FILTERED'          => 'N',
            'ENTITY_REGISTRY_TYPE' => 'ORDER',
        ];

        $res = OrderPropsTable::add(array_merge($fields, $prop));
        if (!$res->isSuccess()) {
            throw new MigrationException("Cannot add property '{$prop['CODE']}'. ".implode("\n", $res->getErrorMessages()));
        }
    }
}
