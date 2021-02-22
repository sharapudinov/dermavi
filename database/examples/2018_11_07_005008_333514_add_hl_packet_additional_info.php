<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlPacketAdditionalInfo20181107005008333514 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'PacketAdditionalInfo',
            'TABLE_NAME' => 'packet_additional_info',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => 'Дополнительная информация по трейсингу',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_PACKET_GUID',
                'XML_ID' => 'UF_PACKET_GUID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Системный идентификатор пакета',
                    'en' => 'Packet GUID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Системный идентификатор пакета',
                    'en' => 'Packet GUID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Системный идентификатор пакета',
                    'en' => 'Packet GUID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Системный идентификатор пакета',
                    'en' => 'Packet GUID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Системный идентификатор пакета',
                    'en' => 'Packet GUID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_BOX_CREATE_DATE',
                'XML_ID' => 'UF_BOX_CREATE_DATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Дата комплектации бокса',
                    'en' => 'Date complect box',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Дата комплектации бокса',
                    'en' => 'Date complect box',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Дата комплектации бокса',
                    'en' => 'Date complect box',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Дата комплектации бокса',
                    'en' => 'Date complect box',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Дата комплектации бокса',
                    'en' => 'Date complect box',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STONE_GUID',
                'XML_ID' => 'UF_STONE_GUID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Системный идентификатор алмаза, из которого сделан бриллиант',
                    'en' => 'ID stone',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Системный идентификатор алмаза, из которого сделан бриллиант',
                    'en' => 'ID stone',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Системный идентификатор алмаза, из которого сделан бриллиант',
                    'en' => 'ID stone',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Системный идентификатор алмаза, из которого сделан бриллиант',
                    'en' => 'ID stone',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Системный идентификатор алмаза, из которого сделан бриллиант',
                    'en' => 'ID stone',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STONE_WEIGHT',
                'XML_ID' => 'UF_STONE_WEIGHT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Масса исходного алмаза',
                    'en' => 'Weight stone',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Масса исходного алмаза',
                    'en' => 'Weight stone',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Масса исходного алмаза',
                    'en' => 'Weight stone',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Масса исходного алмаза',
                    'en' => 'Weight stone',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Масса исходного алмаза',
                    'en' => 'Weight stone',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STONE_SIZE_ID',
                'XML_ID' => 'UF_STONE_SIZE_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Укрупненный размер исходного алмаза',
                    'en' => 'Size stone',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Укрупненный размер исходного алмаза',
                    'en' => 'Size stone',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Укрупненный размер исходного алмаза',
                    'en' => 'Size stone',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Укрупненный размер исходного алмаза',
                    'en' => 'Size stone',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Укрупненный размер исходного алмаза',
                    'en' => 'Size stone',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_OWNERSHIP_ID',
                'XML_ID' => 'UF_OWNERSHIP_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Собственность исходного алмаза',
                    'en' => 'Rought ownership',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Собственность исходного алмаза',
                    'en' => 'Rought ownership',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Собственность исходного алмаза',
                    'en' => 'Rought ownership',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Собственность исходного алмаза',
                    'en' => 'Rought ownership',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Собственность исходного алмаза',
                    'en' => 'Rought ownership',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CUTTER_GUID',
                'XML_ID' => 'UF_CUTTER_GUID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Идентификатор огранщика, который последним работал над камнем',
                    'en' => 'Cutter persona GUID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Идентификатор огранщика, который последним работал над камнем',
                    'en' => 'Cutter persona GUID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Идентификатор огранщика, который последним работал над камнем',
                    'en' => 'Cutter persona GUID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Идентификатор огранщика, который последним работал над камнем',
                    'en' => 'Cutter persona GUID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Идентификатор огранщика, который последним работал над камнем',
                    'en' => 'Cutter persona GUID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_PERSONAS_QTY',
                'XML_ID' => 'UF_PERSONAS_QTY',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Количество уникальных исполнителей, зафиксированных системой',
                    'en' => 'Personas QTY',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Количество уникальных исполнителей, зафиксированных системой',
                    'en' => 'Personas QTY',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Количество уникальных исполнителей, зафиксированных системой',
                    'en' => 'Personas QTY',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Количество уникальных исполнителей, зафиксированных системой',
                    'en' => 'Personas QTY',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Количество уникальных исполнителей, зафиксированных системой',
                    'en' => 'Personas QTY',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_LOT_BEGIN_DATE',
                'XML_ID' => 'UF_LOT_BEGIN_DATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Дата начала обработки лота',
                    'en' => 'Lot begin date',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Дата начала обработки лота',
                    'en' => 'Lot begin date',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Дата начала обработки лота',
                    'en' => 'Lot begin date',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Дата начала обработки лота',
                    'en' => 'Lot begin date',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Дата начала обработки лота',
                    'en' => 'Lot begin date',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_LOT_END_DATE',
                'XML_ID' => 'UF_LOT_END_DATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Дата окончания обработки лота',
                    'en' => 'Lot end time',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Дата окончания обработки лота',
                    'en' => 'Lot end time',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Дата окончания обработки лота',
                    'en' => 'Lot end time',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Дата окончания обработки лота',
                    'en' => 'Lot end time',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Дата окончания обработки лота',
                    'en' => 'Lot end time',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STONE_FORM_ID',
                'XML_ID' => 'UF_STONE_FORM_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Укрупненная форма исходного алмаза',
                    'en' => 'Stone form ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Укрупненная форма исходного алмаза',
                    'en' => 'Stone form ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Укрупненная форма исходного алмаза',
                    'en' => 'Stone form ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Укрупненная форма исходного алмаза',
                    'en' => 'Stone form ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Укрупненная форма исходного алмаза',
                    'en' => 'Stone form ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_CREATE',
                'XML_ID' => 'UF_DATE_CREATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Дата создания',
                    'en' => 'Date create',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DATE_UPDATE',
                'XML_ID' => 'UF_DATE_UPDATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Дата обновления',
                    'en' => 'Date update',
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
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('packet_additional_info')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}
