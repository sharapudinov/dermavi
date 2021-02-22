<?php

use App\Helpers\WebForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddWebformDataForPopupHeader20201029150426852156 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        if (!CModule::IncludeModule("form")) {
            throw new MigrationException('Модуль формы не найден');
        }
        global $strError;

        // Create a new webform
        $id = CForm::Set([
            'NAME'             => 'Заказать обратный звонок',
            'SID'              => 'GET_CALLBACK',
            'arSITE'           => ['s1','s2','s3'],
            'DESCRIPTION'      => '',
            'DESCRIPTION_TYPE' => 'text',
            'STAT_EVENT1'      => 'form',
            "arMENU"           => ["ru" => "Заказ обратного звонка", "en" => "Get call back"],
        ], false, 'N');

        if (!$id) {
            throw new MigrationException('Форма не создана: '.$strError);
        }

        // Get the webform
        $form = CForm::GetBySID('GET_CALLBACK')->Fetch();
        $id   = $form['ID'];

        // Add new statuses
        $status = CFormStatus::Set([
            'FORM_ID'             => $id,
            'TITLE'               => 'DEFAULT',
            'DEFAULT_VALUE'       => 'Y',
            "arPERMISSION_VIEW"   => [2],
            "arPERMISSION_MOVE"   => [2],
            "arPERMISSION_EDIT"   => [2],
            "arPERMISSION_DELETE" => [2],
        ], false, 'N');
        if (!$status) {
            echo $strError."\n";
            throw new \Exception('Ошибка создания веб формы');
        }

        // Add new fields
        $arFields = [
            'NAME'  => [
                'SID'              => 'user_name',
                'COMMENTS'         => 'user_name',
                'TITLE'            => 'Имя пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'SURNAME'   => [
                'SID'              => 'user_surname',
                'COMMENTS'         => 'user_surname',
                'TITLE'            => 'Фамилия пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'EMAIL'      => [
                'SID'              => 'user_email',
                'COMMENTS'         => 'user_email',
                'TITLE'            => 'Email',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'QUESTION' => [
                'SID'              => 'user_question',
                'COMMENTS'         => 'user_question',
                'TITLE'            => 'Вопрос пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'THEME'      => [
                'SID'              => 'user_theme',
                'COMMENTS'         => 'user_theme',
                'TITLE'            => 'Тема',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],

        ];

        foreach ($arFields as $field) {
            if (!CFormField::Set($field, false, 'N')) {
                echo $strError."\n";
                throw new \Exception('Ошибка записи в веб форму значения');
            }
        }

        //Генерируем почтовый шаблон
        CForm::SetMailTemplate($id, "Y", '', true);
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
