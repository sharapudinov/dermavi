<?php

use App\Core\BitrixProperty\Property;
use App\Models\Delivery\DeliveryZone;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Зоны доставки"
 * Class UpdateDeliveryZoneTable20200402135725390687
 */
class UpdateDeliveryZoneTable20200402135725390687 extends BitrixMigration
{
    /** @var array|string[] Массив старых полей */
    private const OLD_PROPERTIES = [
        'UF_PRICE' => ['name' => 'Цена до 0.5 кг'],
        'UF_PRICE_2' => ['name' => 'Цена до 1 кг'],
        'UF_PRICE_3' => ['name' => 'Цена до 2 кг']
    ];

    /** @var array|string[] Массив новых полей */
    private const NEW_PROPERTIES = [
        'UF_PRICE' => ['name' => 'До 0.5 кг, руб'],
        'UF_PRICE_2' => ['name' => '0.5 - 1 кг, руб'],
        'UF_PRICE_3' => ['name' => '1 - 1.5 кг, руб'],
        'UF_PRICE_4' => ['name' => '1.5 - 2 кг, руб'],
        'UF_PRICE_5' => ['name' => '2 - 2.5 кг, руб'],
        'UF_PRICE_6' => ['name' => '2.5 - 3 кг, руб'],
        'UF_TO_DOOR' => ['name' => 'До двери', 'type' => 'boolean']
    ];

    /**
     * Удаляет поля
     *
     * @param array|string[] $fieldsArray Массив полей для удаления
     *
     * @throws Exception
     */
    private function deleteFields(array $fieldsArray): void
    {
        $properties = Property::getUserFields(DeliveryZone::TABLE_CODE, array_keys($fieldsArray));
        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }
    }

    /**
     * Создает поля
     *
     * @param array|string[] $propertiesArray Массив полей для создания
     *
     * @throws Exception
     */
    private function createFields(array $propertiesArray): void
    {
        $entityId = 'HLBLOCK_' . highloadblock(DeliveryZone::TABLE_CODE)['ID'];
        foreach ($propertiesArray as $propertyCode => $property) {
            (new UserField())->constructDefault($entityId, $propertyCode)
                ->setXmlId($propertyCode)
                ->setUserType($property['type'] ?? 'string')
                ->setLangDefault('ru', $property['name'])
                ->setLangDefault('en', $property['name'])
                ->setLangDefault('cn', $property['name'])
                ->add();
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
        $this->deleteFields(self::OLD_PROPERTIES);
        $this->createFields(self::NEW_PROPERTIES);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->deleteFields(self::NEW_PROPERTIES);
        $this->createFields(self::OLD_PROPERTIES);
    }
}
