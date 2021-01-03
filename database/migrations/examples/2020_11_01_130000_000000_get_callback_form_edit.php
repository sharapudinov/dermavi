<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;

class GetCallbackFormEdit20201101130000000000 extends BitrixMigration
{
    public const FORM_CODE = 'GET_CALLBACK';

    /**
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {
        Loader::includeModule('form');

        $form = CForm::GetBySID(static::FORM_CODE)->Fetch();
        $formId = $form['ID'];
        if (!$formId) {
            throw new MigrationException('Веб-форма ' . static::FORM_CODE . ' не найдена');
        }

        $fieldsList = [
            'COMMENT' => [
                'SID' => 'user_comment',
                'COMMENTS' => 'user_comment',
                'TITLE' => 'Комментарий',
                'ACTIVE' => 'Y',
                'FORM_ID' => $formId,
                'FIELD_TYPE' => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE' => 'Y',
                'arANSWER' => [
                    [
                        'FIELD_TYPE' => 'text',
                        'MESSAGE' => ' ',
                        'ACTIVE' => 'Y',
                    ]
                ],
            ],
            'THEME' => [
                'SID' => 'user_phone',
                'COMMENTS' => 'user_phone',
                'TITLE' => 'Телефон',
                'ACTIVE' => 'Y',
                'FORM_ID' => $formId,
                'FIELD_TYPE' => 'text',
                'IN_RESULTS_TABLE' => 'Y',
                'IN_EXCEL_TABLE' => 'Y',
                'arANSWER' => [
                    [
                        'FIELD_TYPE' => 'text',
                        'MESSAGE' => ' ',
                        'ACTIVE' => 'Y',
                    ]
                ],
            ],
        ];

        global $strError;
        foreach ($fieldsList as $field) {
            $curField = (new CFormField())->GetBySID($field['SID'], $formId)->Fetch();
            if ($curField) {
                continue;
            }
            if (!(new CFormField())->Set($field, false, 'N')) {
                throw new MigrationException('Ошибка при сохранении поля: ' . $strError);
            }
        }

        $deleteFields = ['user_theme', 'user_surname', 'user_question'];
        foreach ($deleteFields as $sid) {
            $curField = (new CFormField())->GetBySID($sid, $formId)->Fetch();
            if ($curField) {
                (new CFormField())->Delete($curField['ID'], 'N');
            }
        }

        // Удаляем почтовые шаблоны
        $iterator = CEventMessage::GetList($by = 'id', $order = 'asc', ['TYPE_ID' => 'FORM_FILLING_' . static::FORM_CODE]);
        while ($item = $iterator->Fetch()) {
            CEventMessage::Delete($item['ID']);
        }

        // Генерируем почтовые шаблоны
        $mailTemplates = CForm::SetMailTemplate($formId, 'Y', '', true);

        if ($mailTemplates) {
            CForm::Set(['arMAIL_TEMPLATE' => array_column($mailTemplates, 'ID')], $formId);
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function down(): void
    {
        //
    }
}
