<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AutoAddUfUfClientPbToEntityUser20200608131201099577 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function up()
    {
        $fields = array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_CLIENT_PB',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => 'UF_CLIENT_PB',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 'a:4:{s:13:"DEFAULT_VALUE";i:0;s:7:"DISPLAY";s:8:"CHECKBOX";s:5:"LABEL";a:2:{i:0;s:2:"да";i:1;s:0:"";}s:14:"LABEL_CHECKBOX";s:2:"да";}',
  'EDIT_FORM_LABEL' => 
  array (
    'ru' => 'Клиент PB',
    'en' => 'Client PB',
    'cn' => 'Client PB',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'ru' => '',
    'en' => '',
    'cn' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'ru' => '',
    'en' => '',
    'cn' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'ru' => '',
    'en' => '',
    'cn' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'ru' => '',
    'en' => '',
    'cn' => '',
  ),
);

        $this->addUF($fields);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws MigrationException
     */
    public function down()
    {
        $id = $this->getUFIdByCode('USER', 'UF_CLIENT_PB');
        if (!$id) {
            throw new MigrationException('Не найдено пользовательское свойство для удаления');
        }

        (new CUserTypeEntity())->delete($id);
    }
}
