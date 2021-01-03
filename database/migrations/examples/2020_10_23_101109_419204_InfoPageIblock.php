<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class InfoPageIblock20201023101109419204 extends BitrixMigration
{
    private $iblockTypeId = 'pages';
    private $iblockCode = 'info_pages';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $ib = new CIBlock;

        $iblockId = $ib->add([
            'NAME' => 'Информационные страницы',
            'CODE' => $this->iblockCode,
            'SITE_ID' => 's2',
            'IBLOCK_TYPE_ID' => $this->iblockTypeId,
            'VERSION' => 2,
            'GROUP_ID' => ['2' =>'R'],
            'LIST_PAGE_URL' => '',
            'DETAIL_PAGE_URL' => '',
            'FIELDS' => [
                'CODE' => [
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => [
                        'UNIQUE' => 'Y'
                    ]
                ]
            ]
        ]);

        if (!$iblockId) {
            throw new MigrationException('Ошибка при добавлении инфоблока '.$ib->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     */
    public function down()
    {
        $this->deleteIblockByCode($this->iblockCode);
    }
}
