<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Main\Loader;

/**
 * Класс, описывающий миграцию для изменения свойств "Дата доставки/самовывоза" в заказе у обоих типов лиц
 * Необходимо это потому, что при типе "Дата" битрикс записывает дату, но выдает ошибку ввода
 * либо на этапе создания заказа, либо на этапе возврата денег
 * Class UpdateBitrixOrderDeliveryDateProperties20200311103846081275
 */
class UpdateBitrixOrderDeliveryDateProperties20200311103846081275 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'DELIVERY_DATE';

    /** @var array|int[] $propertiesIds Массив идентификаторов свойств */
    private $propertiesIds;

    /**
     * UpdateBitrixOrderDeliveryDateProperties20200311103846081275 constructor.
     */
    public function __construct()
    {
        Loader::IncludeModule('sale');

        $userTypesQuery = CSalePersonType::GetList();
        $userTypesIds = [];
        while ($userType = $userTypesQuery->GetNext()) {
            $userTypesIds[] = $userType['ID'];
        }

        $properties = CSaleOrderProps::GetList([], ['PERSON_TYPE_ID' => $userTypesIds, 'CODE' => self::PROPERTY_CODE]);
        while ($property = $properties->GetNext()) {
            $this->propertiesIds[] = $property['ID'];
        }
    }

    /**
     * Перебирает массив идентификаторов свойств и обновляет их
     *
     * @param string $type Тип свойства
     *
     * @return void
     */
    private function update(string $type): void
    {
        foreach ($this->propertiesIds as $propertyId) {
            CSaleOrderProps::Update($propertyId, ['TYPE' => $type]);
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update('STRING');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update('DATE');
    }
}
