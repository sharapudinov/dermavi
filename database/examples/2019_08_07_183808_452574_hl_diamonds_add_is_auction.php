<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixIblockHelper\HLblock;

class HlDiamondsAddIsAuction20190807183808452574 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_AUCTION',
                'XML_ID' => 'UF_IS_AUCTION',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Аукционный камень',
                        'en' => 'Auction diamond',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Аукционный камень',
                        'en' => 'Auction diamond',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Аукционный камень',
                        'en' => 'Auction diamond',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Аукционный камень',
                        'en' => 'Auction diamond',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Аукционный камень',
                        'en' => 'Auction diamond',
                    ],
            ],
        ];

        foreach ($fields as $field) {
            $this->addUF($field);
        }
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_IS_AUCTION'];
        $res = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
            ]
        );
        while ($field = $res->Fetch()) {
            if (!in_array($field['FIELD_NAME'], $fields)) {
                continue;
            }
            (new CUserTypeEntity)->Delete($field['ID']);
        }
    }
}
