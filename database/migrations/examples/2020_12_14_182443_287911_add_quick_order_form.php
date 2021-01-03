<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddQuickOrderForm20201214182443287911 extends BitrixMigration
{
    private string $formCode = 'QUICK_ORDER';

    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        if (!CModule::IncludeModule("form")) {
            throw new MigrationException('Модуль формы не найден');
        }
        global $strError;

        $id = CForm::Set(
            [
                'NAME'             => 'Быстрый заказ',
                'SID'              => $this->formCode,
                'arSITE'           => ['s1', 's2', 's3'],
                'DESCRIPTION'      => '',
                'DESCRIPTION_TYPE' => 'text',
                'STAT_EVENT1'      => 'form',
                "arMENU"           => ["ru" => "Быстрый заказ", "en" => "Quick order"],
            ],
            false,
            'N'
        );

        if (!$id) {
            throw new MigrationException('Форма не создана: ' . $strError);
        }

        // Add new statuses
        $status = CFormStatus::Set(
            [
                'FORM_ID'             => $id,
                'TITLE'               => 'DEFAULT',
                'DEFAULT_VALUE'       => 'Y',
                "arPERMISSION_VIEW"   => [2],
                "arPERMISSION_MOVE"   => [2],
                "arPERMISSION_EDIT"   => [2],
                "arPERMISSION_DELETE" => [2],
            ],
            false,
            'N'
        );

        if (!$status) {
            echo $strError . "\n";
            throw new \Exception('Ошибка создания веб формы');
        }

        $arFields = [
            'NAME'         => [
                'SID'              => 'user_name',
                'COMMENTS'         => 'user_name',
                'TITLE'            => 'Имя пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'REQUIRED'         => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'PHONE'        => [
                'SID'              => 'user_phone',
                'COMMENTS'         => 'user_phone',
                'TITLE'            => 'Телефон',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'REQUIRED'         => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'EMAIL'        => [
                'SID'              => 'user_email',
                'COMMENTS'         => 'user_email',
                'TITLE'            => 'Email',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'REQUIRED'         => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'COMMENT'      => [
                'SID'              => 'user_comment',
                'COMMENTS'         => 'user_comment',
                'TITLE'            => 'Комментарий',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'PRODUCT_LINK' => [
                'SID'              => 'product_link',
                'COMMENTS'         => 'product_link',
                'TITLE'            => 'Ссылка на товар',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'REQUIRED'         => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'PRODUCT_ID'   => [
                'SID'              => 'product_id',
                'COMMENTS'         => 'product_id',
                'TITLE'            => 'ID товара',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'REQUIRED'         => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
        ];

        foreach ($arFields as $field) {
            if (!CFormField::Set($field, false, 'N')) {
                echo $strError . "\n";
                echo $field['NAME'] . "\n";
                throw new \Exception('Ошибка записи в веб форму значения');
            }
        }

        //Генерируем почтовый шаблон
        CForm::SetMailTemplate($id, "Y", '', true);
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
