<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable as HLTable;
use Bitrix\Main\{Entity\DataManager,
    Entity\ReferenceField,
    Loader,
    LoaderException,
    ObjectPropertyException,
    SystemException,
    ArgumentException};

const DELIVERY_TARIFF = 'DeliveryTariff';
const FIAS_LOCATION = 'FiasLocation';
const DELIVERY_ZONE = 'DeliveryZone';

class UpdatePriceFile20200804115143204947 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function up()
    {
        $csv = $this->getDataCsv(__DIR__ . '/files/2020_08_04_115143_204947_update_price_file.csv'); // получение данных в файле
        $this->deleteOldDelivery();
        foreach ($csv as $value) {
            $this->updateDelivery($value);
        }
    }

    /**
     * Получение сущности HL блока
     * @param string $name
     * @return string
     * @throws SystemException
     */
    public function getEntityClass(string $name): string
    {
        $entity = HLTable::compileEntity($name);
        return $entity->getDataClass();
    }

    /**
     * Получение содержимого CSV файла
     * @param string $file
     * @return array|MigrationException
     */
    public function getDataCsv(string $file)
    {
        $result = [];
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 200, ";")) !== FALSE) {
                $result[] = $data;
            }
            fclose($handle);

            return $result;
        }

        return new MigrationException('Файл ' . $file . ' не доступен');
    }

    /**
     * Удаление старых тарифов
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws Exception
     */
    public function deleteOldDelivery(): void
    {
        Loader::includeModule("highloadblock");
        // получаю сущность hl Тарифы доставки
        /** @var DataManager $hlDeliveryTariffDataClass */
        $hlDeliveryTariffDataClass = $this->getEntityClass(DELIVERY_TARIFF);

        $rsData = $hlDeliveryTariffDataClass::getList(array(
            'runtime' => [
                // откуда
                new ReferenceField(
                    'FIAS_LOCATION_FROM',
                    $this->getEntityClass(FIAS_LOCATION),
                    ['=this.UF_FROM_LOCATION_ID' => 'ref.ID']
                ),
                // куда
                new ReferenceField(
                    'FIAS_LOCATION_TO',
                    $this->getEntityClass(FIAS_LOCATION),
                    ['=this.UF_TO_LOCATION_ID' => 'ref.ID']
                )
            ],
            'select' => [
                'DELIVERY_TO' => 'FIAS_LOCATION_TO.UF_NAME_RU', // куда
                'DELIVERY_FROM' => 'FIAS_LOCATION_FROM.UF_NAME_RU', // откуда
                'ID'
            ],
            'filter' => [
                'DELIVERY_FROM' => 'Смоленск'
            ],
        ));
        while ($arData = $rsData->Fetch()) {
            // удаление старых тарифов
            $hlDeliveryTariffDataClass::delete($arData['ID']);
        }
    }

    /**
     * Обновление данных в HL блоках
     * @param $value
     * @throws LoaderException
     * @throws SystemException
     * @throws Exception
     */
    public function updateDelivery($value): void
    {
        Loader::includeModule("highloadblock");
        // получаю сущность hl Тарифы доставки
        /** @var DataManager $hlDeliveryTariffDataClass */
        $hlDeliveryTariffDataClass = $this->getEntityClass(DELIVERY_TARIFF);
        /** @var DataManager $hlDeliveryZoneClass */
        $hlDeliveryZoneClass = $this->getEntityClass(DELIVERY_ZONE);

        $rsData = $hlDeliveryTariffDataClass::getList(array(
            'runtime' => [
                // откуда
                new ReferenceField(
                    'FIAS_LOCATION_FROM',
                    $this->getEntityClass(FIAS_LOCATION),
                    ['=this.UF_FROM_LOCATION_ID' => 'ref.ID']
                ),
                // куда
                new ReferenceField(
                    'FIAS_LOCATION_TO',
                    $this->getEntityClass(FIAS_LOCATION),
                    ['=this.UF_TO_LOCATION_ID' => 'ref.ID']
                ),
                // зона доставки
                new ReferenceField(
                    'DELIVERY_ZONE',
                    $this->getEntityClass(DELIVERY_ZONE),
                    ['=this.UF_ZONE_ID' => 'ref.ID']
                ),
            ],
            'select' => [
                'DELIVERY_TO' => 'FIAS_LOCATION_TO.UF_NAME_RU', // куда
                'DELIVERY_FROM' => 'FIAS_LOCATION_FROM.UF_NAME_RU', // откуда
                'UF_ZONE_ID', // ID зоны доставки
                'DELIVERY_ZONE_TO_DOOR' => 'DELIVERY_ZONE.UF_TO_DOOR', // до двери или до пункта самовывоза
                'ID'
            ],
            //'order' => ['ID' => 'ASC'],
            'filter' => [
                'DELIVERY_FROM' => 'Москва',
                'DELIVERY_TO' => $value[1]
            ],
        ));
        while ($arData = $rsData->Fetch()) {
            // обновление HL блока Тарифы доставки
            $deliveryData = [
                'UF_FROM_LOCATION_ID' => 61, // 61 это ID Смоленска
                'UF_DAYS' => $value[2], // время доставки
            ];
            $hlDeliveryTariffDataClass::update($arData['ID'], $deliveryData);

            // обновление HL блока Зоны доставки
            if ($arData['DELIVERY_ZONE_TO_DOOR'] == '1') { // проверка до двери или до пункта самовывоза
                $zoneLocationData = [
                    'UF_CODE' => $value[3], // код
                    'UF_PRICE' => $value[8], // До 0.5 кг, руб
                    'UF_PRICE_2' => $value[9], // 0.5 - 1 кг, руб
                    'UF_PRICE_3' => $value[10], // 1 - 1.5 кг, руб
                    'UF_PRICE_4' => $value[11], // 1.5 - 2 кг, руб
                    'UF_PRICE_5' => '', // 2 - 2.5 кг, руб
                    'UF_PRICE_6' => '', // 2.5 - 3 кг, руб
                ];
            } else {
                $zoneLocationData = [
                    'UF_CODE' => $value[3], // код
                    'UF_PRICE' => $value[4], // До 0.5 кг, руб
                    'UF_PRICE_2' => $value[5], // 0.5 - 1 кг, руб
                    'UF_PRICE_3' => $value[6], // 1 - 1.5 кг, руб
                    'UF_PRICE_4' => $value[7], // 1.5 - 2 кг, руб
                    'UF_PRICE_5' => '', // 2 - 2.5 кг, руб
                    'UF_PRICE_6' => '', // 2.5 - 3 кг, руб
                ];
            }

            $hlDeliveryZoneClass::update($arData['UF_ZONE_ID'], $zoneLocationData);
            $hlDeliveryZoneClass::delete(26);
            $hlDeliveryZoneClass::delete(7);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws Exception
     */
    public function down()
    {
        //
    }
}
