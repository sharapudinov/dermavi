<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AutoAddUfUfBankToEntityUser20200608131235656866 extends BitrixMigration
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
  'FIELD_NAME' => 'UF_BANK',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'UF_BANK',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => [
      "SIZE" => 20,
      "ROWS" => 1,
      'REGEXP' => '',
      'MIN_LENGTH' => 0,
      'MAX_LENGTH' => 0,
      'DEFAULT_VALUE' => ""
  ],
  'EDIT_FORM_LABEL' => 
  array (
    'ru' => 'Банк',
    'en' => 'Bank',
    'cn' => 'Bank',
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
        $id = $this->getUFIdByCode('USER', 'UF_BANK');
        if (!$id) {
            throw new MigrationException('Не найдено пользовательское свойство для удаления');
        }

        (new CUserTypeEntity())->delete($id);
    }
}
