<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\About\Office;

class AddOfficeOpeningHoursFields20201001204057468513 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = $this->getIblockIdByCode(Office::IBLOCK_CODE);

        $this->addIblockElementProperty(
            [
                'NAME' => 'Режим работы (рус)',
                'SORT' => 557,
                'CODE' => 'OPENING_HOURS_RU',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Режим работы (анг)',
                'SORT' => 558,
                'CODE' => 'OPENING_HOURS_EN',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Режим работы (кит)',
                'SORT' => 559,
                'CODE' => 'OPENING_HOURS_CN',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        return true;
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = $this->getIblockIdByCode(Office::IBLOCK_CODE);

        $this->deleteIblockElementPropertyByCode($iblockId, 'OPENING_HOURS_RU');
        $this->deleteIblockElementPropertyByCode($iblockId, 'OPENING_HOURS_EN');
        $this->deleteIblockElementPropertyByCode($iblockId, 'OPENING_HOURS_CN');

        return true;
    }
}
