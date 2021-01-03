<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\HighloadBlock;
use Arrilot\BitrixMigrations\Constructors\UserField;

class ShipmentTables20191129145014641989 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->createZoneTables();
        $this->createFiasLocationTables();
        $this->createDeliveryTables();
        $this->createPickpointTables();
    }

    public function createFiasLocationTables()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('FiasLocation', 'app_fias_location')
            ->setLang('ru', 'Fias локации')
            ->setLang('en', 'Fias locations')
            ->add();

        $entityId = 'HLBLOCK_'.$hblockId;

        (new UserField())->constructDefault($entityId, 'FIAS_ID')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Fias ID')
            ->setLangDefault('en', 'Fias ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setShowInList(true)
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_EN')
            ->setLangDefault('ru', 'Наименование (анг)')
            ->setLangDefault('en', 'Name (eng)')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_CN')
            ->setLangDefault('ru', 'Наименование (cn)')
            ->setLangDefault('en', 'Name (cn)')
            ->add();

        (new UserField())->constructDefault($entityId, 'DADATA_INFO')
            ->setMandatory(true)
            ->setLangDefault('ru', 'DaData info')
            ->setLangDefault('en', 'DaData info')
            ->add();
    }

    public function createDeliveryTables()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('DeliveryTariff', 'app_delivery_tariff')
            ->setLang('ru', 'Тарифы доставки')
            ->setLang('en', 'Delivery tariff')
            ->add();

        $entityId = 'HLBLOCK_'.$hblockId;

        (new UserField())->constructDefault($entityId, 'FROM_LOCATION_ID')
            ->setMandatory(true)
            ->setShowInList(true)
            ->setUserTypeHL('app_fias_location', 'UF_NAME_RU')
            ->setLangDefault('ru', 'Location ID')
            ->setLangDefault('en', 'Location ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'TO_LOCATION_ID')
            ->setMandatory(true)
            ->setShowInList(true)
            ->setUserTypeHL('app_fias_location', 'UF_NAME_RU')
            ->setLangDefault('ru', 'Location ID')
            ->setLangDefault('en', 'Location ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'ZONE_ID')
            ->setMandatory(true)
            ->setUserTypeHL('app_delivery_zone', 'UF_CODE')
            ->setLangDefault('ru', 'Зона')
            ->setLangDefault('en', 'Zone')
            ->add();

        (new UserField())->constructDefault($entityId, 'DAYS')
            ->setLangDefault('ru', 'Время доставки')
            ->setLangDefault('en', 'Delivery time')
            ->add();
    }

    public function createPickpointTables()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('DeliveryPickpoint', 'app_delivery_pickpoint')
            ->setLang('ru', 'Пункты самовывоза')
            ->setLang('en', 'Pickpoint addresses')
            ->add();

        $entityId = 'HLBLOCK_'.$hblockId;

        (new UserField())->constructDefault($entityId, 'LOCATION_ID')
            ->setMandatory(true)
            ->setUserTypeHL('app_fias_location', 'UF_NAME_RU')
            ->setLangDefault('ru', 'Location ID')
            ->setLangDefault('en', 'Location ID')
            ->add();

        (new UserField())->constructDefault($entityId, 'NAME_RU')
            ->setShowInList(true)
            ->setLangDefault('ru', 'Наименование (рус)')
            ->setLangDefault('en', 'Name (rus)')
            ->add();

        (new UserField())->constructDefault($entityId, 'PHONES')
            ->setLangDefault('ru', 'Телефоны')
            ->setLangDefault('en', 'Phones')
            ->add();

        (new UserField())->constructDefault($entityId, 'SORT')
            ->setLangDefault('ru', 'Приоритет в списке')
            ->setLangDefault('en', 'Sort priority')
            ->setUserType('integer')
            ->setSettings(['DEFAULT_VALUE' => 500])
            ->add();
    }

    public function createZoneTables()
    {
        $hblockId = (new HighloadBlock())
            ->constructDefault('DeliveryZone', 'app_delivery_zone')
            ->setLang('ru', 'Зоны доставки')
            ->setLang('en', 'Delivery zones')
            ->add();

        $entityId = 'HLBLOCK_'.$hblockId;

        (new UserField())->constructDefault($entityId, 'CODE')
            ->setMandatory(true)
            ->setShowInList(true)
            ->setLangDefault('ru', 'Код')
            ->setLangDefault('en', 'Code')
            ->add();

        (new UserField())->constructDefault($entityId, 'PRICE')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Цена до 0.5 кг')
            ->setLangDefault('en', 'Price before 0.5kg')
            ->add();

        (new UserField())->constructDefault($entityId, 'PRICE_2')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Цена до 1 кг')
            ->setLangDefault('en', 'Price before 1kg')
            ->add();

        (new UserField())->constructDefault($entityId, 'PRICE_3')
            ->setMandatory(true)
            ->setLangDefault('ru', 'Цена до 2 кг')
            ->setLangDefault('en', 'Price before 2kg')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblocksToDelete = ['app_delivery_zone', 'app_delivery_pickpoint', 'app_delivery_tariff', 'app_fias_location'];

        foreach ($iblocksToDelete as $iblockCode) {
            /** @var \Bitrix\Main\ORM\Data\AddResult $result */
            $id = highloadblock($iblockCode)['ID'];
            if ($id) {
                $result = \Bitrix\Highloadblock\HighloadBlockTable::delete($id);
            }
        }
    }
}
