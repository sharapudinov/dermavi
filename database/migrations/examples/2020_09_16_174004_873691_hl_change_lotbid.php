<?php

use App\Models\Auctions\LotBid;
use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class HlChangeLotbid20200916174004873691 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId       = HLblock::getByTableName(LotBid::TABLE_CODE)["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
        $this->addUF(
            [
                'ENTITY_ID'         => $highloadBlockEntityId,
                'FIELD_NAME'        => 'UF_OVERBID_SEND',
                'USER_TYPE_ID'      => 'string',
                'MANDATORY'         => 'N',
                'EDIT_FORM_LABEL'   =>
                    [
                        'ru' => 'Отправлено оповещении о перебитии ставки',
                        'en' => 'overbidSend',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Отправлено оповещении о перебитии ставки',
                        'en' => 'overbidSend',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Отправлено оповещении о перебитии ставки',
                        'en' => 'overbidSend',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'ru' => 'Отправлено оповещении о перебитии ставки',
                        'en' => 'overbidSend',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'ru' => 'Отправлено оповещении о перебитии ставки',
                        'en' => 'overbidSend',
                    ]
                ]
        );
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
