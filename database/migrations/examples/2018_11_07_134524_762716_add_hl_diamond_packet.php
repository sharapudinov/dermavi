<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;

class AddHlDiamondPacket20181107134524762716 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     */
    public function up()
    {
        $fields = [
            'NAME' => 'DiamondPacket',
            'TABLE_NAME' => 'diamond_packet',
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
            "NAME" => 'Информация о пакете',
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
                'MANDATORY' => 'Y',
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
                'FIELD_NAME' => 'UF_PACKET_NUMBER',
                'XML_ID' => 'UF_PACKET_NUMBER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Номер пакета',
                    'en' => 'Packet number',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Номер пакета',
                    'en' => 'Packet number',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Номер пакета',
                    'en' => 'Packet number',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Номер пакета',
                    'en' => 'Packet number',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Номер пакета',
                    'en' => 'Packet number',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_DIAMOND_QTY',
                'XML_ID' => 'UF_DIAMOND_QTY',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Количество бриллиантов в пакете',
                    'en' => 'Diamond qty',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Количество бриллиантов в пакете',
                    'en' => 'Diamond qty',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Количество бриллиантов в пакете',
                    'en' => 'Diamond qty',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Количество бриллиантов в пакете',
                    'en' => 'Diamond qty',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Количество бриллиантов в пакете',
                    'en' => 'Diamond qty',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_RF_CERT',
                'XML_ID' => 'UF_IS_RF_CERT',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Наличие сертификата РФ',
                    'en' => 'RF cert',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Наличие сертификата РФ',
                    'en' => 'RF cert',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Наличие сертификата РФ',
                    'en' => 'RF cert',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Наличие сертификата РФ',
                    'en' => 'RF cert',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Наличие сертификата РФ',
                    'en' => 'RF cert',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_GIA_CERT',
                'XML_ID' => 'UF_IS_GIA_CERT',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Наличие сертификата GIA',
                    'en' => 'GIA cert',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Наличие сертификата GIA',
                    'en' => 'GIA cert',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Наличие сертификата GIA',
                    'en' => 'GIA cert',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Наличие сертификата GIA',
                    'en' => 'GIA cert',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Наличие сертификата GIA',
                    'en' => 'GIA cert',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_FANCY',
                'XML_ID' => 'UF_IS_FANCY',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Признак цветные',
                    'en' => 'Fancy',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Признак цветные',
                    'en' => 'Fancy',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Признак цветные',
                    'en' => 'Fancy',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Признак цветные',
                    'en' => 'Fancy',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Признак цветные',
                    'en' => 'Fancy',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_CERT_NUMBER',
                'XML_ID' => 'UF_RF_CERT_NUMBER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Номер сертификата РФ',
                    'en' => 'RF cert number',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Номер сертификата РФ',
                    'en' => 'RF cert number',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Номер сертификата РФ',
                    'en' => 'RF cert number',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Номер сертификата РФ',
                    'en' => 'RF cert number',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Номер сертификата РФ',
                    'en' => 'RF cert number',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_PACKET_WEIGHT',
                'XML_ID' => 'UF_PACKET_WEIGHT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Масса пакета',
                    'en' => 'Packet weight',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Масса пакета',
                    'en' => 'Packet weight',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Масса пакета',
                    'en' => 'Packet weight',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Масса пакета',
                    'en' => 'Packet weight',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Масса пакета',
                    'en' => 'Packet weight',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_RORM_ID',
                'XML_ID' => 'UF_RF_RORM_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ формы огранки по ТУ',
                    'en' => 'RF form ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ формы огранки по ТУ',
                    'en' => 'RF form ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ формы огранки по ТУ',
                    'en' => 'RF form ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ формы огранки по ТУ',
                    'en' => 'RF form ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ формы огранки по ТУ',
                    'en' => 'RF form ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_QUAL_ID',
                'XML_ID' => 'UF_RF_QUAL_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ качества по ТУ',
                    'en' => 'RF qual ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ качества по ТУ',
                    'en' => 'RF qual ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ качества по ТУ',
                    'en' => 'RF qual ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ качества по ТУ',
                    'en' => 'RF qual ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ качества по ТУ',
                    'en' => 'RF qual ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_COLOR_ID',
                'XML_ID' => 'UF_RF_COLOR_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ цвета по ТУ',
                    'en' => 'RF color ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ цвета по ТУ',
                    'en' => 'RF color ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ цвета по ТУ',
                    'en' => 'RF color ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета по ТУ',
                    'en' => 'RF color ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета по ТУ',
                    'en' => 'RF color ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_SIZE_ID',
                'XML_ID' => 'UF_RF_SIZE_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ размера по ТУ',
                    'en' => 'RF size ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ размера по ТУ',
                    'en' => 'RF size ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ размера по ТУ',
                    'en' => 'RF size ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ размера по ТУ',
                    'en' => 'RF size ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ размера по ТУ',
                    'en' => 'RF size ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_RF_QUALITY_ID',
                'XML_ID' => 'UF_RF_QUALITY_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ группы качества огранки по ТУ',
                    'en' => 'RF quality group ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ группы качества огранки по ТУ',
                    'en' => 'RF quality group ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ группы качества огранки по ТУ',
                    'en' => 'RF quality group ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ группы качества огранки по ТУ',
                    'en' => 'RF quality group ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ группы качества огранки по ТУ',
                    'en' => 'RF quality group ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_CERT_NUMBER',
                'XML_ID' => 'UF_GIA_CERT_NUMBER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Номер сертификата GIA',
                    'en' => 'GIA cert number',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Номер сертификата GIA',
                    'en' => 'GIA cert number',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Номер сертификата GIA',
                    'en' => 'GIA cert number',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Номер сертификата GIA',
                    'en' => 'GIA cert number',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Номер сертификата GIA',
                    'en' => 'GIA cert number',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_COLOR_ID',
                'XML_ID' => 'UF_GIA_COLOR_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ цвета по GIA',
                    'en' => 'GIA color ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ цвета по GIA',
                    'en' => 'GIA color ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ цвета по GIA',
                    'en' => 'GIA color ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета по GIA',
                    'en' => 'GIA color ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета по GIA',
                    'en' => 'GIA color ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_QUAL_ID',
                'XML_ID' => 'UF_GIA_QUAL_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ качества по GIA',
                    'en' => 'GIA qual ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ качества по GIA',
                    'en' => 'GIA qual ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ качества по GIA',
                    'en' => 'GIA qual ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ качества по GIA',
                    'en' => 'GIA qual ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ качества по GIA',
                    'en' => 'GIA qual ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_QUALITY_ID',
                'XML_ID' => 'UF_GIA_QUALITY_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ качества огранки по GIA',
                    'en' => 'GIA quality group ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ качества огранки по GIA',
                    'en' => 'GIA quality group ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ качества огранки по GIA',
                    'en' => 'GIA quality group ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ качества огранки по GIA',
                    'en' => 'GIA quality group ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ качества огранки по GIA',
                    'en' => 'GIA quality group ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_FORM_ID',
                'XML_ID' => 'UF_GIA_FORM_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ формы по GIA',
                    'en' => 'GIA form ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ формы по GIA',
                    'en' => 'GIA form ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ формы по GIA',
                    'en' => 'GIA form ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ формы по GIA',
                    'en' => 'GIA form ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ формы по GIA',
                    'en' => 'GIA form ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_FLUOR_ID',
                'XML_ID' => 'UF_GIA_FLUOR_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ флюоресценции по GIA',
                    'en' => 'GIA fuor ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ флюоресценции по GIA',
                    'en' => 'GIA fuor ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ флюоресценции по GIA',
                    'en' => 'GIA fuor ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ флюоресценции по GIA',
                    'en' => 'GIA fuor ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ флюоресценции по GIA',
                    'en' => 'GIA fuor ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_FCOLOR_ID',
                'XML_ID' => 'UF_GIA_FCOLOR_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ цвета флюоресценции по GIA',
                    'en' => 'GIA fluor color ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ цвета флюоресценции по GIA',
                    'en' => 'GIA fluor color ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ цвета флюоресценции по GIA',
                    'en' => 'GIA fluor color ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета флюоресценции по GIA',
                    'en' => 'GIA fluor color ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета флюоресценции по GIA',
                    'en' => 'GIA fluor color ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FANCY_COLOR_ID',
                'XML_ID' => 'UF_FANCY_COLOR_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ цвета для цветиков',
                    'en' => 'Fancy color ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ цвета для цветиков',
                    'en' => 'Fancy color ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ цвета для цветиков',
                    'en' => 'Fancy color ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета для цветиков',
                    'en' => 'Fancy color ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ цвета для цветиков',
                    'en' => 'Fancy color ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FINTENSITY_ID',
                'XML_ID' => 'UF_FINTENSITY_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ интенсивности цвета для цветиков',
                    'en' => 'Fancy intencity ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ интенсивности цвета для цветиков',
                    'en' => 'Fancy intencity ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ интенсивности цвета для цветиков',
                    'en' => 'Fancy intencity ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ интенсивности цвета для цветиков',
                    'en' => 'Fancy intencity ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ интенсивности цвета для цветиков',
                    'en' => 'Fancy intencity ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_DEPTH',
                'XML_ID' => 'UF_GIA_DEPTH',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Высота, значения в процентах от 25 до 80%',
                    'en' => 'GIA depth',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Высота, значения в процентах от 25 до 80%',
                    'en' => 'GIA depth',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Высота, значения в процентах от 25 до 80%',
                    'en' => 'GIA depth',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Высота, значения в процентах от 25 до 80%',
                    'en' => 'GIA depth',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Высота, значения в процентах от 25 до 80%',
                    'en' => 'GIA depth',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_TABLE',
                'XML_ID' => 'UF_GIA_TABLE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Площадка, значения в процентах от 25 до 80%',
                    'en' => 'GIA table',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Площадка, значения в процентах от 25 до 80%',
                    'en' => 'GIA table',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Площадка, значения в процентах от 25 до 80%',
                    'en' => 'GIA table',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Площадка, значения в процентах от 25 до 80%',
                    'en' => 'GIA table',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Площадка, значения в процентах от 25 до 80%',
                    'en' => 'GIA table',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_SYMMETRY',
                'XML_ID' => 'UF_SYMMETRY',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ симметрии',
                    'en' => 'Symmetry',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ симметрии',
                    'en' => 'Symmetry',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ симметрии',
                    'en' => 'Symmetry',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ симметрии',
                    'en' => 'Symmetry',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ симметрии',
                    'en' => 'Symmetry',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_POLISH',
                'XML_ID' => 'UF_GIA_POLISH',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ полировки',
                    'en' => 'GIA polish',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ полировки',
                    'en' => 'GIA polish',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ полировки',
                    'en' => 'GIA polish',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ полировки',
                    'en' => 'GIA polish',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ полировки',
                    'en' => 'GIA polish',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_GIA_CULET',
                'XML_ID' => 'UF_GIA_CULET',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Ключ кулеты',
                    'en' => 'GIA culet',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ кулеты',
                    'en' => 'GIA culet',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ кулеты',
                    'en' => 'GIA culet',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ кулеты',
                    'en' => 'GIA culet',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ кулеты',
                    'en' => 'GIA culet',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FPARTNER_ID',
                'XML_ID' => 'UF_FPARTNER_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Обработчик',
                    'en' => 'Factory partner ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Обработчик',
                    'en' => 'Factory partner ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Обработчик',
                    'en' => 'Factory partner ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Обработчик',
                    'en' => 'Factory partner ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Обработчик',
                    'en' => 'Factory partner ID',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_SALE_COST',
                'XML_ID' => 'UF_SALE_COST',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Стоимость реализации',
                    'en' => 'Sale cost',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Стоимость реализации',
                    'en' => 'Sale cost',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Стоимость реализации',
                    'en' => 'Sale cost',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Стоимость реализации',
                    'en' => 'Sale cost',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Стоимость реализации',
                    'en' => 'Sale cost',
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
                    'ru' => 'Ключ собственности',
                    'en' => 'Ownership ID',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Ключ собственности',
                    'en' => 'Ownership ID',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Ключ собственности',
                    'en' => 'Ownership ID',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Ключ собственности',
                    'en' => 'Ownership ID',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Ключ собственности',
                    'en' => 'Ownership ID',
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
        $highloadBlockId = HLblock::getByTableName('diamond_packet')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}
