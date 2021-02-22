<?php

use App\Helpers\Form;
use App\Helpers\WebForm;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddJewelryWebform20191225001002894161 extends BitrixMigration
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
            throw new MigrationException('Module form not found');
        }
        global $strError;

        // Create a new webform
        $id = CForm::Set([
            'NAME'             => 'Заказ ювелирного изделия',
            'SID'              => WebForm::JEWELRY_ORDER,
            'arSITE'           => ['s2'],
            'DESCRIPTION'      => '',
            'DESCRIPTION_TYPE' => 'text',
            'STAT_EVENT1'      => 'form',
            //'STAT_EVENT2'      => 'visitor_form_obj',
            "arMENU"           => ["ru" => "Заказ ювелирного изделия", "en" => "Jewelry order"],
        ], false, 'N');

        if (!$id) {
            throw new MigrationException('Form not created: '.$strError);
        }

        // Get the webform
        $form = CForm::GetBySID(WebForm::JEWELRY_ORDER)->Fetch();
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
            throw new \Exception('Error creating web form');
        }

        // Add new fields
        $arFields = [
            'FIRSTNAME'  => [
                'SID'              => 'user_firstname',
                'COMMENTS'         => 'user_firstname',
                'TITLE'            => 'Имя пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'LASTNAME'   => [
                'SID'              => 'user_lastname',
                'COMMENTS'         => 'user_lastname',
                'TITLE'            => 'Фамилия пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'SECONDNAME' => [
                'SID'              => 'user_secondname',
                'COMMENTS'         => 'user_secondname',
                'TITLE'            => 'Отчество пользователя',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'PHONE'      => [
                'SID'              => 'user_phone',
                'COMMENTS'         => 'user_phone',
                'TITLE'            => 'Телефон',
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
            'CITY'       => [
                'SID'              => 'user_city',
                'COMMENTS'         => 'user_city',
                'TITLE'            => 'Телефон',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],
            'COUNTRY'    => [
                'SID'              => 'user_country',
                'COMMENTS'         => 'user_country',
                'TITLE'            => 'Страна',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],

            'ENGRAVING' => [
                'SID'              => 'engraving',
                'COMMENTS'         => 'engraving',
                'TITLE'            => 'Гравировка',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],

            'CURRENCY' => [
                'SID'              => 'currency',
                'COMMENTS'         => 'currency',
                'TITLE'            => 'Код валюты заказа',
                "ACTIVE"           => "Y",
                'FORM_ID'          => $id,
                'FIELD_TYPE'       => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE'   => 'Y',
                'arANSWER'         => [['FIELD_TYPE' => 'text', 'MESSAGE' => ' ', 'ACTIVE' => 'Y']],
            ],

            'PRODUCT' => [
                'SID'              => 'product_id',
                'COMMENTS'         => 'product_id',
                'TITLE'            => 'ID Товара',
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
                echo $strError."\n";
                throw new \Exception('Error creating web form field');
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
        if (!CModule::IncludeModule("form")) {
            throw new MigrationException('Module form not found');
        }
        global $strError;

        $form = CForm::GetBySID(WebForm::JEWELRY_ORDER)->Fetch();
        if (!$form) {
            throw new MigrationException('Form not found');
        }
        if (!CForm::Delete($form['ID'], 'N')) {
            throw new MigrationException('Form is not deleted: '.$strError);
        }
    }
}
