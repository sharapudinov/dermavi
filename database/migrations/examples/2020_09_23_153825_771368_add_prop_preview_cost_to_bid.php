<?php

use App\Models\Auctions\LotBid;
use App\Models\Auctions\PBLotBid;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddPropPreviewCostToBid20200923153825771368 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        foreach ([LotBid::TABLE_CODE, PBLotBid::TABLE_CODE] as $tableCode) {
            $highloadBlockId       = HLblock::getByTableName($tableCode)["ID"];
            $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
            $this->addUF(
                [
                    'ENTITY_ID'         => $highloadBlockEntityId,
                    'FIELD_NAME'        => 'UF_COST_BEFORE_BIT',
                    'XML_ID'            => 'UF_COST_BEFORE_BIT',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    'EDIT_FORM_LABEL'   =>
                        [
                            'ru' => 'Ставка до перебития',
                            'en' => 'prev bid',
                        ],
                    'LIST_COLUMN_LABEL' =>
                        [
                            'ru' => 'Ставка до перебития',
                            'en' => 'prev bid',
                        ],
                    'LIST_FILTER_LABEL' =>
                        [
                            'ru' => 'Ставка до перебития',
                            'en' => 'prev bid',
                        ],
                    'ERROR_MESSAGE'     =>
                        [
                            'ru' => 'Ставка до перебития',
                            'en' => 'prev bid',
                        ],
                    'HELP_MESSAGE'      =>
                        [
                            'ru' => 'Ставка до перебития',
                            'en' => 'prev bid',
                        ],
                ]
            );

            $this->addUF(
                [
                    'ENTITY_ID'         => $highloadBlockEntityId,
                    'FIELD_NAME'        => 'UF_LAST_USER_BIT',
                    'XML_ID'            => 'UF_LAST_USER_BIT',
                    'USER_TYPE_ID'      => 'string',
                    'MANDATORY'         => 'N',
                    'EDIT_FORM_LABEL'   =>
                        [
                            'ru' => 'Предыдущая ставка пользователя',
                            'en' => 'prev user bid',
                        ],
                    'LIST_COLUMN_LABEL' =>
                        [
                            'ru' => 'Предыдущая ставка пользователя',
                            'en' => 'prev user bid',
                        ],
                    'LIST_FILTER_LABEL' =>
                        [
                            'ru' => 'Предыдущая ставка пользователя',
                            'en' => 'prev user bid',
                        ],
                    'ERROR_MESSAGE'     =>
                        [
                            'ru' => 'Предыдущая ставка пользователя',
                            'en' => 'prev user bid',
                        ],
                    'HELP_MESSAGE'      =>
                        [
                            'ru' => 'Предыдущая ставка пользователя',
                            'en' => 'prev user bid',
                        ],
                ]
            );
            $this->addUF(
                [
                    'ENTITY_ID'         => $highloadBlockEntityId,
                    'FIELD_NAME'        => 'UF_IS_REBIDDING',
                    'XML_ID'            => 'UF_IS_REBIDDING',
                    'USER_TYPE_ID'      => 'boolean',
                    'MANDATORY'         => 'N',
                    'EDIT_FORM_LABEL'   =>
                        [
                            'ru' => 'Активная переторжка',
                            'en' => 'is rebidding',
                        ],
                    'LIST_COLUMN_LABEL' =>
                        [
                            'ru' => 'Активная переторжка',
                            'en' => 'is rebidding',
                        ],
                    'LIST_FILTER_LABEL' =>
                        [
                            'ru' => 'Активная переторжка',
                            'en' => 'is rebidding',
                        ],
                    'ERROR_MESSAGE'     =>
                        [
                            'ru' => 'Активная переторжка',
                            'en' => 'is rebidding',
                        ],
                    'HELP_MESSAGE'      =>
                        [
                            'ru' => 'Активная переторжка',
                            'en' => 'is rebidding',
                        ],
                ]
            );
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
        //
    }
}
