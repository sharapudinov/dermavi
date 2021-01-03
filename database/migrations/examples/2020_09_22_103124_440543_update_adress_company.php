<?php

use App\Models\Delivery\FiasLocation;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockTable as HLTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Arrilot\BitrixCacher\Cache;

class UpdateAdressCompany20200922103124440543 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $csv = $this->getDataArray(__DIR__ . '/files/2020_09_22_103124_440543_update_adress_company.csv');
        $this->deliveryPickPointElementsDelete();
        foreach ($csv as $value) {
            // проверия на наличие FIAS локации
            $fiasLocation = FiasLocation::query()->filter(['UF_NAME_RU' => $value[0]])->first();
            if ($fiasLocation["UF_NAME_RU"]) {
                // если локация есть добавляю новый пункт выдачи, с привязкой к FIAS локации
                $this->getEntityClass('DeliveryPickpoint')::add([
                    'UF_LOCATION_ID' => $fiasLocation["ID"],
                    'UF_NAME_RU' => $value[1],
                    'UF_PHONES' => $value[2]
                ]);
            } else {
                // если локация не найдена добавляю новую, и беру ID новой FIAS локации, для добавления пункта выдачи
                $newId = $this->getEntityClass('FiasLocation')::add([
                    'UF_NAME_RU' => $value[0]
                ]);
                $this->getEntityClass('DeliveryPickpoint')::add([
                    'UF_LOCATION_ID' => $newId,
                    'UF_NAME_RU' => $value[1],
                    'UF_PHONES' => $value[2]
                ]);
            }
        }
        Cache::flushAll();
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

    /**
     * Удаление элементов из таблицы Пункты самовывоза
     */
    public function deliveryPickPointElementsDelete()
    {
        Loader::includeModule("highloadblock");
        $deliveryPickPointHL = $this->getEntityClass('DeliveryPickpoint');
        while ($fiasElement = $deliveryPickPointHL::getList()->Fetch()) {
            $deliveryPickPointHL::delete($fiasElement['ID']);
        }
    }

    /**
     * Получение сущности HL блока
     * @param string $tableName
     * @return DataManager
     * @throws SystemException
     */
    public function getEntityClass(string $tableName): string
    {
        $entity = HLTable::compileEntity($tableName);
        return $entity->getDataClass();
    }

    /**
     * Получение содержимого CSV файла
     * @param string $file
     * @return array|MigrationException
     */
    public function getDataArray(string $file)
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
}
