<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable as HLTable;
use Bitrix\Main\Entity\DataManager;

const DELIVERY_TARIFF = 'DeliveryTariff';
const FIAS_LOCATION = 'FiasLocation';
const DELIVERY_ZONE = 'DeliveryZone';
const DOOR = 1;
const DOOR_AND_SERVICE = 2;

class ImportPrice20200901074716921106 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        CModule::IncludeModule('highloadblock');
        $file_csv = $this->getDataCsv(__DIR__ . '/files/2020_08_04_115143_204947_update_price_file.csv'); // получение данных в файле

        /** @var DataManager $hlDeliveryTariffDataClass */
        $hlDeliveryTariffDataClass = $this->getEntityClass(DELIVERY_TARIFF);

        /** @var DataManager $hlFiasLocationDataClass */
        $hlFiasLocationDataClass = $this->getEntityClass(FIAS_LOCATION);

        /** @var DataManager $hlDeliveryZoneClass */
        $hlDeliveryZoneClass = $this->getEntityClass(DELIVERY_ZONE);

        // очищаю таблицы от старых данных
        $this->deleteHLElements();

        foreach ($file_csv as $csv_data) {

            // нахожу ID зон доставок по элементу из файла
            $ids_zone_delivery = $this->findZoneDelivery($csv_data);

            // если зоны доставки найдены
            if($ids_zone_delivery)
            {
                foreach ($ids_zone_delivery as $id_delivery_zone){
                    $this->addTariffElement($csv_data, $id_delivery_zone);
                }
            }
            else // если зоны доставки не найдены, добавляем новые
            {
                $ids_zone_delivery = $this->addDeliveryZoneElement($csv_data);
                foreach ($ids_zone_delivery as $id_delivery_zone){
                    $this->addTariffElement($csv_data, $id_delivery_zone);
                }
            }
        }
    }

    /**
     * Очищение таблиц с тарифами и зонами доставки от старых данных
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function deleteHLElements(): void
    {
        /** @var DataManager $hlDeliveryZoneClass */
        $hlDeliveryZoneClass = $this->getEntityClass(DELIVERY_ZONE);
        $deliveryZoneData = $hlDeliveryZoneClass::getList([
            'select' => ['ID'],
            ]);
        while ($arDeliveryZoneData = $deliveryZoneData->Fetch()){
            $hlDeliveryZoneClass::Delete($arDeliveryZoneData['ID']);
        }

        /** @var DataManager $hlDeliveryTariffDataClass */
        $hlDeliveryTariffDataClass = $this->getEntityClass(DELIVERY_TARIFF);
        $deliveryTariffData = $hlDeliveryTariffDataClass::getList([
            'select' => ['ID'],
        ]);
        while ($arDeliveryTariffData = $deliveryTariffData->Fetch()){
            $hlDeliveryTariffDataClass::Delete($arDeliveryTariffData['ID']);
        }
    }

    /**
     * Добавляет новую зону доставки
     *
     * @param $fileElement
     * @return mixed
     * @throws Exception
     */
    public function addDeliveryZoneElement($fileElement)
    {
        /** @var DataManager $hlDeliveryZoneClass */
        $hlDeliveryZoneClass = $this->getEntityClass(DELIVERY_ZONE);


        if ($this->getTypeDelivery($fileElement) === DOOR_AND_SERVICE) {
            // заполняю до пункта самовывоза
            $result = $hlDeliveryZoneClass::add([
                'UF_CODE' => $fileElement[3],
                'UF_TO_DOOR' => 0,
                'UF_PRICE' => $fileElement[4], // До 0.5 кг, руб
                'UF_PRICE_2' => $fileElement[5], // 0.5 - 1 кг, руб
                'UF_PRICE_3' => $fileElement[6], // 1 - 1.5 кг, руб
                'UF_PRICE_4' => $fileElement[7], // 1.5 - 2 кг, руб
            ]);
            $id = $result->getId();
            $ids[] = [
                'ID' => $id,
                'UF_TO_DOOR' => 0
            ];
            // заполняю до двери
            $result = $hlDeliveryZoneClass::add([
                'UF_CODE' => $fileElement[3],
                'UF_TO_DOOR' => 1,
                'UF_PRICE' => $fileElement[8], // До 0.5 кг, руб
                'UF_PRICE_2' => $fileElement[9], // 0.5 - 1 кг, руб
                'UF_PRICE_3' => $fileElement[10], // 1 - 1.5 кг, руб
                'UF_PRICE_4' => $fileElement[11], // 1.5 - 2 кг, руб
            ]);
            $ids[$result->getId()] = [
                'ID' => $result->getId(),
                'UF_TO_DOOR' => 1
            ];
        }

        if ($this->getTypeDelivery($fileElement) === DOOR) {
            // заполняю до двери
            $result = $hlDeliveryZoneClass::add([
                'UF_CODE' => $fileElement[3],
                'UF_TO_DOOR' => 1,
                'UF_PRICE' => $fileElement[8], // До 0.5 кг, руб
                'UF_PRICE_2' => $fileElement[9], // 0.5 - 1 кг, руб
                'UF_PRICE_3' => $fileElement[10], // 1 - 1.5 кг, руб
                'UF_PRICE_4' => $fileElement[11], // 1.5 - 2 кг, руб
            ]);
            $ids[$result->getId()] = [
                'ID' => $result->getId(),
                'UF_TO_DOOR' => 1
            ];
        }

        return $ids;
    }

    /**
     * Добавляет элемент в таблицу тарифов
     *
     * @param $fileElement
     * @param $idZoneDelivery
     * @return array|int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws Exception
     */
    public function addTariffElement($fileElement, $idZoneDelivery)
    {
        /** @var DataManager $hlDeliveryTariffDataClass */
        $hlDeliveryTariffDataClass = $this->getEntityClass(DELIVERY_TARIFF);

        $result = $hlDeliveryTariffDataClass::add([
            'UF_FROM_LOCATION_ID' => 1, // откуда
            'UF_TO_LOCATION_ID' => $this->getIdCity($fileElement), // куда
            'UF_ZONE_ID' => $idZoneDelivery['ID'], // ID зоны
            'UF_DAYS' => $fileElement[2], // время доставки
        ]);

        return $result->getId();
    }

    /**
     * Получает ID города, по элементу из файла
     *
     * @param $fileElement
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIdCity($fileElement)
    {
        /** @var DataManager $hlFiasLocationDataClass */
        $hlFiasLocationDataClass = $this->getEntityClass(FIAS_LOCATION);
        $fiasLocationData = $hlFiasLocationDataClass::getList([
            'select' => ['ID', 'UF_NAME_RU'],
            'filter' => ['UF_NAME_RU' => $fileElement[1]]
        ]);
        $fiasLocation = $fiasLocationData->Fetch();

        return $fiasLocation['ID'];
    }

    /**
     * Вернет ID зоны доставки, если она ранее была создана
     *
     * @param $elementInFile
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function findZoneDelivery($elementInFile): ?array
    {
        /** @var DataManager $hlDeliveryZoneClass */
        $hlDeliveryZoneClass = $this->getEntityClass(DELIVERY_ZONE);

        $result = null;

        // проверка есть ли такой элемент с кодом в зонах доставки
        if($this->getTypeDelivery($elementInFile) === DOOR_AND_SERVICE) {
            $deliveryZoneData = $hlDeliveryZoneClass::getList([
                'select' => ['ID', 'UF_CODE', 'UF_TO_DOOR'],
                'filter' => [
                    'UF_CODE' => $elementInFile[3], // код зоны доставки
                    'UF_TO_DOOR' => [0,1]
                ]]);
            while ($arDeliveryZoneData = $deliveryZoneData->Fetch()){
                $result[$arDeliveryZoneData['ID']] = [
                    'ID' => $arDeliveryZoneData['ID'],
                    'UF_TO_DOOR' => $arDeliveryZoneData['UF_TO_DOOR']
                ];
            }

            if(count($result)>1){
                return $result;
            } else {
                return null;
            }
        }
        if($this->getTypeDelivery($elementInFile) === DOOR) {
            $deliveryZoneData = $hlDeliveryZoneClass::getList([
                'select' => [
                    'ID',
                    'UF_CODE',
                    'UF_TO_DOOR'
                ],
                'filter' => [
                    'UF_CODE' => $elementInFile[3], // код зоны доставки
                    'UF_TO_DOOR' => 1 // получает тип зоны доставки
                ]
            ]);
            while ($arDeliveryZoneData = $deliveryZoneData->Fetch()){
                $result[$arDeliveryZoneData['ID']] = [
                    'ID' => $arDeliveryZoneData['ID'],
                    'UF_TO_DOOR' => $arDeliveryZoneData['UF_TO_DOOR']
                ];
            }
            if(count($result) === 1){
                return $result;
            }

            return null;
        }
    }

    /**
     * Определяет тип доставки до двери и самовывоз, или только до двери
     *
     * @param $type
     * @return int|int[]
     */
    public function getTypeDelivery($type)
    {
        if($type[4] !== '') { return DOOR_AND_SERVICE; } // до двери и самовывоз
        return DOOR;  // только до двери
    }

    /**
     * Получение содержимого CSV файла
     * @param string $file
     * @return array|MigrationException
     */
    public function getDataCsv(string $file)
    {
        $result = [];
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 200, ";")) !== false) {
                $result[] = $data;
            }
            fclose($handle);

            return $result;
        }

        return new MigrationException('Файл ' . $file . ' не доступен');
    }

    /**
     * Получение сущности HL блока
     * @param string $name
     * @return string
     * @throws SystemException
     * @throws \Bitrix\Main\SystemException
     */
    public function getEntityClass(string $name): string
    {
        $entity = HLTable::compileEntity($name);
        return $entity->getDataClass();
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
