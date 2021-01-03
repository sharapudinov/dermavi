<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class InfoPageIblockType20201023100157351482 extends BitrixMigration
{
    private $iblockTypeId = 'pages';

    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        CModule::IncludeModule('iblock');
        $cbt = new CIBlockType;
        $cbtRes = $cbt->Add(
            [
                'ID'       => $this->iblockTypeId,
                'SECTIONS' => 'Y',
                'IN_RSS'   => 'N',
                'SORT'     => 100,
                'LANG'     => [
                    'ru' => [
                        'NAME' => 'Страницы',
                    ],
                ],
            ]
        );

        if (!$cbtRes) {
            throw new MigrationException('Ошибка при добавлении типа инфоблока ' . $cbt->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        CModule::IncludeModule('iblock');

        global $DB;
        $DB->StartTransaction();

        if (!CIBlockType::Delete($this->iblockTypeId)) {
            $DB->Rollback();
            throw new MigrationException('Ошибка при удалении типа инфоблока __');
        }

        $DB->Commit();
    }
}
