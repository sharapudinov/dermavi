<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;

/**
 * Class ChangeMarquiseShape20201015170000000000
 * Заменяем в справочниках dict_from и catalog_shape названия Marquis на Marquise
 */
final class ChangeMarquiseShape20201015170000000000 extends BitrixMigration
{
    /** @var array */
    private $hlCache;

    /** @var string */
    private $displayValue = '';

    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $this->displayValue = 'Marquise';
        $this->updateRows();
    }

    /**
     * Reverse the migration.
     * @throws Exception
     */
    public function down()
    {
        $this->displayValue = 'Marquis';
        $this->updateRows();
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function updateRows(): void
    {
        $setDisplayValue = $this->displayValue;
        if (!$setDisplayValue) {
            return;
        }

        Loader::includeModule('highloadblock');

        $dataManager = $this->getHlEntityDataManagerClass('dict_from');
        if ($dataManager) {
            $iterator = $dataManager::getList(
                [
                    'filter' => [
                        '=UF_SHAPE_XML_ID' => 'marquis',
                    ]
                ]
            );
            while ($item = $iterator->fetch()) {
                $dataManager::update(
                    $item['ID'],
                    [
                        'UF_DISPLAY_VALUE_EN' => $setDisplayValue,
                        'UF_DATE_UPDATE' => new Date(),
                    ]
                );
            }
        }

        $dataManager = $this->getHlEntityDataManagerClass('catalog_shape');
        if ($dataManager) {
            $iterator = $dataManager::getList(
                [
                    'filter' => [
                        '=UF_XML_ID' => 'marquis',
                    ]
                ]
            );
            while ($item = $iterator->fetch()) {
                $dataManager::update(
                    $item['ID'],
                    [
                        'UF_NAME' => $setDisplayValue,
                        'UF_DISPLAY_VALUE_EN' => $setDisplayValue,
                        'UF_DATE_UPDATE' => new Date(),
                    ]
                );
            }
        }
    }

    /**
     * @param string $tableName
     * @return Entity|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getHlEntity(string $tableName)
    {
        if (!isset($this->hlCache[$tableName])) {
            $this->hlCache[$tableName] = false;
            $hlFormTableData = HighloadBlockTable::getList(
                [
                    'filter' => [
                        '=TABLE_NAME' => $tableName,
                    ],
                ]
            )->fetch();
            if ($hlFormTableData) {
                $this->hlCache[$tableName] = HighloadBlockTable::compileEntity($hlFormTableData);
            }
        }

        return $this->hlCache[$tableName] ?: null;
    }

    /**
     * @param string $tableName
     * @return string|\Bitrix\Main\ORM\Data\DataManager|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getHlEntityDataManagerClass(string $tableName)
    {
        $formEntity = $this->getHlEntity($tableName);

        return $formEntity ? $formEntity->getDataClass() : null;
    }
}
