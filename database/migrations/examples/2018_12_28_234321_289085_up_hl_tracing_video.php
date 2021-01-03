<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CUserTypeEntity;

class UpHlTracingVideo20181228234321289085 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $highloadBlockId = HLblock::getByTableName('tracing_video_part')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_TABLET_EN',
                'XML_ID' => 'UF_VIDEO_TABLET_EN',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (англ)',
                    'en' => 'Video for tables (eng)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (англ)',
                    'en' => 'Video for tables (eng)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (англ)',
                    'en' => 'Video for tables (eng)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (англ)',
                    'en' => 'Video for tables (eng)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (англ)',
                    'en' => 'Video for tables (eng)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_TABLET_RU',
                'XML_ID' => 'UF_VIDEO_TABLET_RU',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (рус)',
                    'en' => 'Video for tables (rus)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (рус)',
                    'en' => 'Video for tables (rus)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (рус)',
                    'en' => 'Video for tables (rus)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (рус)',
                    'en' => 'Video for tables (rus)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (рус)',
                    'en' => 'Video for tables (rus)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_TABLET_CN',
                'XML_ID' => 'UF_VIDEO_TABLET_CN',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (кит)',
                    'en' => 'Video for tables (cn)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (кит)',
                    'en' => 'Video for tables (cn)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для планшетов (кит)',
                    'en' => 'Video for tables (cn)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (кит)',
                    'en' => 'Video for tables (cn)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для планшетов (кит)',
                    'en' => 'Video for tables (cn)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_MOBILE_EN',
                'XML_ID' => 'UF_VIDEO_MOBILE_EN',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (англ)',
                    'en' => 'Video for mobile (eng)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (англ)',
                    'en' => 'Video for mobile (eng)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (англ)',
                    'en' => 'Video for mobile (eng)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (англ)',
                    'en' => 'Video for mobile (eng)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (англ)',
                    'en' => 'Video for mobile (eng)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_MOBILE_RU',
                'XML_ID' => 'UF_VIDEO_MOBILE_RU',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (рус)',
                    'en' => 'Video for mobile (rus)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (рус)',
                    'en' => 'Video for mobile (rus)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (рус)',
                    'en' => 'Video for mobile (rus)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (рус)',
                    'en' => 'Video for mobile (rus)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (рус)',
                    'en' => 'Video for mobile (rus)',
                ],
            ],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
                'FIELD_NAME' => 'UF_VIDEO_MOBILE_CN',
                'XML_ID' => 'UF_VIDEO_MOBILE_CN',
                'USER_TYPE_ID' => 'file',
                'MANDATORY' => 'N',
                'EDIT_FORM_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (кит)',
                    'en' => 'Video for mobile (cn)',
                ],
                'LIST_COLUMN_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (кит)',
                    'en' => 'Video for mobile (cn)',
                ],
                'LIST_FILTER_LABEL' =>
                [
                    'ru' => 'Видео для мобильных (кит)',
                    'en' => 'Video for mobile (cn)',
                ],
                'ERROR_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (кит)',
                    'en' => 'Video for mobile (cn)',
                ],
                'HELP_MESSAGE' =>
                [
                    'ru' => 'Видео для мобильных (кит)',
                    'en' => 'Video for mobile (cn)',
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
        $highloadBlockId = HLblock::getByTableName('tracing_video_part')["ID"];
        $highloadBlockEntityId = 'HLBLOCK_' . $highloadBlockId;

        $fields = [
            'UF_VIDEO_TABLET_EN',
            'UF_VIDEO_TABLET_RU',
            'UF_VIDEO_TABLET_CN',
            'UF_VIDEO_MOBILE_EN',
            'UF_VIDEO_MOBILE_RU',
            'UF_VIDEO_MOBILE_CN',
        ];
        $res = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => $highloadBlockEntityId,
            ]
        );
        while ($field = $res->Fetch()) {
            if (!in_array($field['FIELD_NAME'], $fields)) {
                continue;
            }
            (new CUserTypeEntity)->Delete($field['ID']);
        }
    }
}
