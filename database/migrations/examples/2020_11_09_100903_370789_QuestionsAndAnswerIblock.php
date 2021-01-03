<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class QuestionsAndAnswerIblock20201109100903370789 extends BitrixMigration
{
    private $iblockTypeId = 'for_customers';
    private $iblockCode = 'customers_questions_and_answer';

    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $ib = new CIBlock;

        $iblockId = $ib->add(
            [
                'NAME'            => 'Вопросы и Ответы',
                'CODE'            => $this->iblockCode,
                'SITE_ID'         => 's2',
                'IBLOCK_TYPE_ID'  => $this->iblockTypeId,
                'VERSION'         => 2,
                'GROUP_ID'        => ['2' => 'R'],
                'LIST_PAGE_URL'   => '',
                'DETAIL_PAGE_URL' => '',
                'FIELDS' => [
                    'SECTION_CODE' => [
                        'IS_REQUIRED' => 'Y',
                        'DEFAULT_VALUE' => [
                            'UNIQUE' => 'Y'
                        ]
                    ]
                ]
            ]
        );

        if (!$iblockId) {
            throw new MigrationException('Ошибка при добавлении инфоблока ' . $ib->LAST_ERROR);
        }
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        $this->deleteIblockByCode($this->iblockCode);
    }
}
