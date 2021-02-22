<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use App\Models\HL\Country;

/**
 * Class AddHlDeliveryAddress20190131210735801185
 */
class AddHlDeliveryAddress20190131210735801185 extends BitrixMigration
{
    /**
     * @var bool
     */
    public $use_transaction = true;
    
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $fields = [
            'NAME' => 'DeliveryAddress',
            'TABLE_NAME' => 'app_delivery_address',
        ];
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока: ' . implode(', ', $errors));
        }

        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        //Добавляем языковые названия для HL-блока
        HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => 'Адреса доставки',
        ]);
        HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "en",
            "NAME" => 'Delivery addresses',
        ]);

        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }
    
        $countryHighloadBlockId = HLblock::getByTableName(Country::TABLE_CODE)["ID"];
        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_USER_ID',
                'XML_ID' => 'UF_USER_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'Y',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Пользователь',
                    'en' => 'User',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Пользователь',
                    'en' => 'User',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Пользователь',
                    'en' => 'User',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Пользователь',
                    'en' => 'User',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Пользователь',
                    'en' => 'User',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_COUNTRY',
                'XML_ID' => 'UF_COUNTRY',
                'USER_TYPE_ID' => 'hlblock',
                'MANDATORY' => 'N',
                'SETTINGS' => [
                    'HLBLOCK_ID' => $countryHighloadBlockId,
                    'HLFIELD_ID' => $this->getUFIdByCode('HLBLOCK_' . $countryHighloadBlockId, 'UF_NAME_RU'),
                    'DEFAULT_VALUE' => '',
                    'DISPLAY' => 'LIST',
                    'LIST_HEIGHT' => 5
                ],
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Страна',
                    'en' => 'Country',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Страна',
                    'en' => 'Country',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Страна',
                    'en' => 'Country',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Страна',
                    'en' => 'Country',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Страна',
                    'en' => 'Country',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_INDEX',
                'XML_ID' => 'UF_INDEX',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Index',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Index',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Index',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Index',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Почтовый индекс',
                    'en' => 'Index',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_REGION',
                'XML_ID' => 'UF_REGION',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Регион',
                    'en' => 'Region',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Регион',
                    'en' => 'Region',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Регион',
                    'en' => 'Region',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Регион',
                    'en' => 'Region',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Регион',
                    'en' => 'Region',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CITY',
                'XML_ID' => 'UF_CITY',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Город',
                    'en' => 'City',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Город',
                    'en' => 'City',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Город',
                    'en' => 'City',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Город',
                    'en' => 'City',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Город',
                    'en' => 'City',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_STREET',
                'XML_ID' => 'UF_STREET',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Улица',
                    'en' => 'Street',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Улица',
                    'en' => 'Street',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Улица',
                    'en' => 'Street',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Улица',
                    'en' => 'Street',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Улица',
                    'en' => 'Street',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_HOUSE',
                'XML_ID' => 'UF_HOUSE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                    [
                        'ru' => 'Дом/Строение',
                        'en' => 'House/Building',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'ru' => 'Дом/Строение',
                        'en' => 'House/Building',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'ru' => 'Дом/Строение',
                        'en' => 'House/Building',
                    ],
                'ERROR_MESSAGE' =>
                    [
                        'ru' => 'Дом/Строение',
                        'en' => 'House/Building',
                    ],
                'HELP_MESSAGE' =>
                    [
                        'ru' => 'Дом/Строение',
                        'en' => 'House/Building',
                    ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FLAT',
                'XML_ID' => 'UF_FLAT',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Квартира/Офис',
                    'en' => 'Flat',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Квартира/Офис',
                    'en' => 'Flat',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Квартира/Офис',
                    'en' => 'Flat',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Квартира/Офис',
                    'en' => 'Flat',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Квартира',
                    'en' => 'Flat',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_DEFAULT',
                'XML_ID' => 'UF_IS_DEFAULT',
                'USER_TYPE_ID' => 'boolean',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Использовать по-умолчанию',
                    'en' => 'Use as default',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Использовать по-умолчанию',
                    'en' => 'Use as default',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Использовать по-умолчанию',
                    'en' => 'Use as default',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Использовать по-умолчанию',
                    'en' => 'Use as default',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Использовать по-умолчанию',
                    'en' => 'Use as default',
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
     * @throws \Exception
     */
    public function down()
    {
        $highloadBlockId = HLblock::getByTableName('app_delivery_address')["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . implode(', ', $errors));
        }
    }
}
