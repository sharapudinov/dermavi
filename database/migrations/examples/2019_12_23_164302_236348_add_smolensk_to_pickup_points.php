<?php

use App\Core\BitrixProperty\Property;
use App\Core\BitrixProperty\Entity\Property as PropertyEntity;
use App\Models\Sale\PickupPoint;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий миграцию для добавления Смоленска в список пунктов самовывоза
 * Class AddSmolenskToPickupPoints20191223164302236348
 */
class AddSmolenskToPickupPoints20191223164302236348 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var Collection|PropertyEntity[] $properties Коллекция объектов, описывающих варианты св-ва список */
        $properties = Property::getListPropertyValues(PickupPoint::iblockId(), 'USER_ENTITY_TYPE');

        PickupPoint::create([
            'NAME' => 'Смоленск',
            'PROPERTY_VALUES' => [
                'NAME_RU' => 'Смоленск',
                'NAME_EN' => 'Smolensk',
                'NAME_CN' => '斯摩棱斯克',
                'GOOGLE_MAP' => '54.773016,32.098074',
                'CITY_RU' => 'Смоленск',
                'CITY_EN' => 'Smolenks',
                'CITY_CN' => '斯摩棱斯克',
                'ADDRESS_RU' => 'Смоленск, ул. Шкадова, 2',
                'ADDRESS_EN' => 'Smolensk, Shkadova street, 2',
                'ADDRESS_CN' => 'Smolensk, Shkadova street, 2',
                'PHONES' => ['8 (800) 550-78-97'],
                'WORKING_HOURS_RU' => '09:00–17:00',
                'WORKING_HOURS_EN' => '09:00–17:00',
                'WORKING_HOURS_CN' => '09:00–17:00',
                'USER_ENTITY_TYPE' => [
                    $properties->first()->getVariantId(),
                    $properties->last()->getVariantId()
                ]
            ]
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
        /** @var PickupPoint $point Модель пункта самовывоза */
        $point = PickupPoint::filter(['NAME' => 'Смоленск'])->first();
        $point->delete();
    }
}
