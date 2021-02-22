<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

class AddHlWishlist20190408173712555848 extends BitrixMigration
{
    const HL_NAME = 'Wishlist';
    const HL_TABLE_NAME = 'app_wishlist';
    
    /**
     * Run the migration.
     */
    public function up()
    {
        Loader::includeModule('highloadblock');
        
        //Создаем HL-блок
        $fields = array(
            'NAME' => self::HL_NAME,
            'TABLE_NAME' => self::HL_TABLE_NAME,
        );
        $result = HighloadBlockTable::add($fields);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }
        
        $highloadBlockId = $result->getId();
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;
        
        //Добавляем языковые названия для HL-блока
        $result = HighloadBlockLangTable::add([
            "ID" => $highloadBlockId,
            "LID" => "ru",
            "NAME" => "Списки желаний"
        ]);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при добавлении языкового названия для hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }
        
        //Добавляем поля для HL-блока
        $fields = [
            array(
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_FUSER_ID',
                'USER_TYPE_ID' => 'integer',
                'XML_ID' => '',
                'SORT' => '100',
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'I',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => [
                    'SIZE' => 20,
                    'MIN_VALUE' => 0,
                    'MAX_VALUE' => 0,
                    'DEFAULT_VALUE' => ''
                ],
                'EDIT_FORM_LABEL' =>
                    array(
                        'ru' => 'Профиль покупателя',
                        'en' => '',
                    ),
                'LIST_COLUMN_LABEL' =>
                    array(
                        'ru' => 'Профиль покупателя',
                        'en' => '',
                    ),
                'LIST_FILTER_LABEL' =>
                    array(
                        'ru' => 'Профиль покупателя',
                        'en' => '',
                    ),
                'ERROR_MESSAGE' =>
                    array(
                        'ru' => 'Ошибка заполнения поля "Профиль покупателя"',
                        'en' => '',
                    ),
                'HELP_MESSAGE' =>
                    array(
                        'ru' => 'Профиль покупателя',
                        'en' => '',
                    ),
            ),
            array(
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_CREATE_DATE',
                'USER_TYPE_ID' => 'datetime',
                'XML_ID' => '',
                'SORT' => '200',
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'S',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => [
                    'DEFAULT_VALUE' => [
                        'TYPE' => 'NOW',
                        'VALUE' => ''
                    ]
                ],
                'EDIT_FORM_LABEL' =>
                    array(
                        'ru' => 'Дата создания',
                        'en' => '',
                    ),
                'LIST_COLUMN_LABEL' =>
                    array(
                        'ru' => 'Дата создания',
                        'en' => '',
                    ),
                'LIST_FILTER_LABEL' =>
                    array(
                        'ru' => 'Дата создания',
                        'en' => '',
                    ),
                'ERROR_MESSAGE' =>
                    array(
                        'ru' => 'Ошибка заполнения поля "Дата создания"',
                        'en' => '',
                    ),
                'HELP_MESSAGE' =>
                    array(
                        'ru' => 'Дата создания',
                        'en' => '',
                    ),
            ),
            array(
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_PRODUCT_ID',
                'USER_TYPE_ID' => 'integer',
                'XML_ID' => '',
                'SORT' => '300',
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'I',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => [
                    'SIZE' => 20,
                    'MIN_VALUE' => 0,
                    'MAX_VALUE' => 0,
                    'DEFAULT_VALUE' => ''
                ],
                'EDIT_FORM_LABEL' =>
                    array(
                        'ru' => 'ID товара',
                        'en' => '',
                    ),
                'LIST_COLUMN_LABEL' =>
                    array(
                        'ru' => 'ID товара',
                        'en' => '',
                    ),
                'LIST_FILTER_LABEL' =>
                    array(
                        'ru' => 'ID товара',
                        'en' => '',
                    ),
                'ERROR_MESSAGE' =>
                    array(
                        'ru' => 'Ошибка заполнения поля "ID товара"',
                        'en' => '',
                    ),
                'HELP_MESSAGE' =>
                    array(
                        'ru' => 'ID товара',
                        'en' => '',
                    ),
            ),
            array(
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_IS_HIDDEN',
                'USER_TYPE_ID' => 'boolean',
                'XML_ID' => '',
                'SORT' => '400',
                'MULTIPLE' => 'N',
                'MANDATORY' => 'Y',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => [
                    'DISPLAY' => 'CHECKBOX',
                    'DEFAULT_VALUE' => 0
                ],
                'EDIT_FORM_LABEL' =>
                    array(
                        'ru' => 'Скрытый',
                        'en' => '',
                    ),
                'LIST_COLUMN_LABEL' =>
                    array(
                        'ru' => 'Скрытый',
                        'en' => '',
                    ),
                'LIST_FILTER_LABEL' =>
                    array(
                        'ru' => 'Скрытый',
                        'en' => '',
                    ),
                'ERROR_MESSAGE' =>
                    array(
                        'ru' => 'Ошибка заполнения поля "Скрытый"',
                        'en' => '',
                    ),
                'HELP_MESSAGE' =>
                    array(
                        'ru' => 'Скрытый',
                        'en' => '',
                    ),
            ),
        ];
        
        foreach ($fields as $field) {
            $this->addUF($field);
        }
    
        //Изменяем типы данных для полей
        $sql = 'ALTER TABLE `' . static::HL_TABLE_NAME . '` MODIFY `UF_IS_HIDDEN` INT(1)';
        Application::getConnection()->query($sql);
        
        //Создаем индексы
        $sql = 'CREATE INDEX IXS_PRODUCT_REQUEST_FUSER_ID ON `' . static::HL_TABLE_NAME . '` (`UF_FUSER_ID`)';
        Application::getConnection()->query($sql);
        
        $sql = 'CREATE UNIQUE INDEX ' . static::HL_TABLE_NAME . '_UF_FUSER_ID_UF_PRODUCT_ID_uindex ON `' . static::HL_TABLE_NAME . '` (UF_FUSER_ID, UF_PRODUCT_ID);';
        Application::getConnection()->query($sql);
        
        //Удаление списков желаний при удалении профилей покупателей
        $sql = 'alter table app_wishlist add constraint app_wishlist_b_sale_fuser_ID_fk foreign key (UF_FUSER_ID) references b_sale_fuser (ID) on delete cascade;';
        Application::getConnection()->query($sql);
    }
    
    /**
     * Reverse the migration
     * @throws MigrationException
     * @throws Bitrix\Main\LoaderException
     */
    public function down()
    {
        Loader::includeModule('highloadblock');
        
        //Удаляем HL-блок
        $highloadBlockId = HLblock::getByTableName(self::HL_TABLE_NAME)["ID"];
        $result = HighloadBlockTable::delete($highloadBlockId);
        if (!$result->isSuccess()) {
            $errors = $result->getErrorMessages();
            throw new MigrationException('Ошибка при удалении hl-блока ' . self::HL_NAME . ': ' . implode(', ', $errors));
        }
    }
}
