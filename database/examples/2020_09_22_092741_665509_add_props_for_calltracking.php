<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\About\Office;

class AddPropsForCalltracking20200922092741665509 extends BitrixMigration
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
                'NAME' => 'Calltracking класс (обычный офис)',
                'SORT' => 554,
                'CODE' => 'CALLTRACKING_CLASS',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Calltracking класс (центральный)',
                'SORT' => 555,
                'CODE' => 'CALLTRACKING_CLASS_HEAD',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Calltracking класс (для СМИ)',
                'SORT' => 556,
                'CODE' => 'CALLTRACKING_CLASS_COMMUNICATIONS',
                'PROPERTY_TYPE' => 'S', // строка
                'IS_REQUIRED'   => 'N',
                'IBLOCK_ID'     => $iblockId
            ]
        );

        $this->addIblockElementProperty(
            [
                'NAME' => 'Использовать Calltracking класс',
                'SORT' => 557,
                'CODE' => 'CALLTRACKING_CLASS_ENABLE',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'VALUES' => [
                    ['VALUE' => 'Y']
                ],
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

        $this->deleteIblockElementPropertyByCode($iblockId, 'CALLTRACKING_CLASS');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CALLTRACKING_CLASS_ENABLE');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CALLTRACKING_CLASS_HEAD');
        $this->deleteIblockElementPropertyByCode($iblockId, 'CALLTRACKING_CLASS_COMMUNICATIONS');

        return true;
    }
}
