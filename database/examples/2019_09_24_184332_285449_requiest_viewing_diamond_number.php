<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Mail\Internal\EventMessageTable;

class RequiestViewingDiamondNumber20190924184332285449 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $e = EventMessageTable::getList([
            'filter' => ['EVENT_NAME' => 'VIEWING_REQUEST'],
        ]);

        $eventMessageData = $e->fetch();

        EventMessageTable::update($eventMessageData['ID'],
            [
                'SUBJECT' => '#UF_SUBJECT#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'MESSAGE' => "Название компании - #UF_COMPANY_NAME# <br>
Сфера деятельности компании - #UF_COMPANY_ACTIVITY# <br>
ИНН - #UF_TAX_ID# <br>
Страна - #UF_COUNTRY# <br>
<br>
Фамилия - #UF_SURNAME# <br>
Имя - #UF_NAME# <br>
Телефон - #UF_PHONE# <br>
Email - #UF_EMAIL# <br>
<br>
Дата показа - #UF_DATE_OF_VIEWING# <br>
Время показа - #UF_TIME_OF_VIEWING# <br>
Комментарий - #UF_COMMENT# <br>
Запрашиваемые позиции - #UF_URL_DIAMOND# <br>
",
            ]
        );

        $highloadBlockId = \Arrilot\BitrixIblockHelper\HLblock::getByTableName(\App\Models\HL\ViewingRequestForm::TABLE_CODE)['ID'];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AUCTION_ID',
                'XML_ID' => 'UF_AUCTION_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Id Аукциона',
                        'en' => 'Auction Id',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Id Аукциона',
                        'en' => 'Auction Id',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Id Аукциона',
                        'en' => 'Auction Id',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Id Аукциона',
                        'en' => 'Auction Id',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Id Аукциона',
                        'en' => 'Auction Id',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_AUCTION_LOT_ID',
                'XML_ID' => 'UF_AUCTION_LOT_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Id Лота Аукциона',
                        'en' => 'Auction Lot Id',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Id Лота Аукциона',
                        'en' => 'Auction Lot Id',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Id Лота Аукциона',
                        'en' => 'Auction Lot Id',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Id Лота Аукциона',
                        'en' => 'Auction Lot Id',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Id Лота Аукциона',
                        'en' => 'Auction Lot Id',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_TYPE',
                'XML_ID' => 'UF_TYPE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Тип',
                        'en' => 'Type',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Тип',
                        'en' => 'Type',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Тип',
                        'en' => 'Type',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Тип',
                        'en' => 'Type',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Тип',
                        'en' => 'Type',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_AUCTION',
                'XML_ID' => 'UF_IS_AUCTION',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Из аукционов(любая точка)',
                        'en' => 'Auction(any point)',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Из аукционов(любая точка)',
                        'en' => 'Auction(any point)',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Из аукционов(любая точка)',
                        'en' => 'Auction(any point)',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Из аукционов(любая точка)',
                        'en' => 'Auction(any point)',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Из аукционов(любая точка)',
                        'en' => 'Auction(any point)',
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
        $highloadBlockId = \Arrilot\BitrixIblockHelper\HLblock::getByTableName(\App\Models\HL\ViewingRequestForm::TABLE_CODE)['ID'];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = ['UF_AUCTION_ID', 'UF_AUCTION_LOT_ID', 'UF_TYPE', 'UF_IS_AUCTION'];
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
