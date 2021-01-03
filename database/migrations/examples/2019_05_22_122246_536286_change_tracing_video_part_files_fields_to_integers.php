<?php

use App\Helpers\LanguageHelper;
use App\Models\Tracing\HL\VideoPart;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class ChangeTracingVideoPartFilesFieldsToIntegers20190522122246536286 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock(VideoPart::TABLE_CODE)['ID'];
        $langs = LanguageHelper::getAvailableLanguages();
        foreach ($langs as $language) {
            $language = strtoupper($language);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_' . $language]
            )->Fetch();
            if ($field['ID']) {
                UserField::delete($field['ID']);
            }

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_MOBILE_' . $language]
            )->Fetch();
            if ($field['ID']) {
                UserField::delete($field['ID']);
            }

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_TABLET_' . $language]
            )->Fetch();
            if ($field['ID']) {
                UserField::delete($field['ID']);
            }
        }

        (new UserField())->constructDefault($entityId, 'VIDEO_EN')
            ->setXmlId('UF_VIDEO_EN')
            ->setLangDefault('ru', 'Видео (англ)')
            ->setLangDefault('en', 'Video (en)')
            ->setLangDefault('cn', 'Video (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_RU')
            ->setXmlId('UF_VIDEO_RU')
            ->setLangDefault('ru', 'Видео (рус)')
            ->setLangDefault('en', 'Video (ru)')
            ->setLangDefault('cn', 'Video (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_CN')
            ->setXmlId('UF_VIDEO_CN')
            ->setLangDefault('ru', 'Видео (кит)')
            ->setLangDefault('en', 'Video (cn)')
            ->setLangDefault('cn', 'Video (cn)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_EN')
            ->setXmlId('UF_VIDEO_MOBILE_EN')
            ->setLangDefault('ru', 'Видео для мобильных (англ)')
            ->setLangDefault('en', 'Video mobile (en)')
            ->setLangDefault('cn', 'Video mobile (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_RU')
            ->setXmlId('UF_VIDEO_MOBILE_RU')
            ->setLangDefault('ru', 'Видео для мобильных (рус)')
            ->setLangDefault('en', 'Video mobile (ru)')
            ->setLangDefault('cn', 'Video mobile (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_CN')
            ->setXmlId('UF_VIDEO_MOBILE_CN')
            ->setLangDefault('ru', 'Видео для мобильных (кит)')
            ->setLangDefault('en', 'Video mobile (cn)')
            ->setLangDefault('cn', 'Video mobile (cn)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_EN')
            ->setXmlId('UF_VIDEO_TABLET_EN')
            ->setLangDefault('ru', 'Видео для планшетов (англ)')
            ->setLangDefault('en', 'Video tablets (en)')
            ->setLangDefault('cn', 'Video tablets (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_RU')
            ->setXmlId('UF_VIDEO_TABLET_RU')
            ->setLangDefault('ru', 'Видео для планшетов (рус)')
            ->setLangDefault('en', 'Video tablets (ru)')
            ->setLangDefault('cn', 'Video tablets (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_CN')
            ->setXmlId('UF_VIDEO_TABLET_CN')
            ->setLangDefault('ru', 'Видео для планшетов (кит)')
            ->setLangDefault('en', 'Video tablets (cn)')
            ->setLangDefault('cn', 'Video tablets (cn)')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $entityId = 'HLBLOCK_' . highloadblock(VideoPart::TABLE_CODE)['ID'];

        $langs = LanguageHelper::getAvailableLanguages();
        foreach ($langs as $language) {
            $language = strtoupper($language);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_' . $language]
            )->Fetch();
            UserField::delete($field['ID']);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_MOBILE_' . $language]
            )->Fetch();
            UserField::delete($field['ID']);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_VIDEO_TABLET_' . $language]
            )->Fetch();
            UserField::delete($field['ID']);
        }

        (new UserField())->constructDefault($entityId, 'VIDEO_EN')
            ->setXmlId('UF_VIDEO_EN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео (англ)')
            ->setLangDefault('en', 'Video (en)')
            ->setLangDefault('cn', 'Video (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_RU')
            ->setXmlId('UF_VIDEO_RU')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео (рус)')
            ->setLangDefault('en', 'Video (ru)')
            ->setLangDefault('cn', 'Video (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_CN')
            ->setXmlId('UF_VIDEO_CN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео (кит)')
            ->setLangDefault('en', 'Video (cn)')
            ->setLangDefault('cn', 'Video (cn)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_EN')
            ->setXmlId('UF_VIDEO_MOBILE_EN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для мобильных (англ)')
            ->setLangDefault('en', 'Video mobile (en)')
            ->setLangDefault('cn', 'Video mobile (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_RU')
            ->setXmlId('UF_VIDEO_MOBILE_RU')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для мобильных (рус)')
            ->setLangDefault('en', 'Video mobile (ru)')
            ->setLangDefault('cn', 'Video mobile (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_MOBILE_CN')
            ->setXmlId('UF_VIDEO_MOBILE_CN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для мобильных (кит)')
            ->setLangDefault('en', 'Video mobile (cn)')
            ->setLangDefault('cn', 'Video mobile (cn)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_EN')
            ->setXmlId('UF_VIDEO_TABLET_EN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для планшетов (англ)')
            ->setLangDefault('en', 'Video tablets (en)')
            ->setLangDefault('cn', 'Video tablets (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_RU')
            ->setXmlId('UF_VIDEO_TABLET_RU')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для планшетов (рус)')
            ->setLangDefault('en', 'Video tablets (ru)')
            ->setLangDefault('cn', 'Video tablets (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'VIDEO_TABLET_CN')
            ->setXmlId('UF_VIDEO_TABLET_CN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Видео для планшетов (кит)')
            ->setLangDefault('en', 'Video tablets (cn)')
            ->setLangDefault('cn', 'Video tablets (cn)')
            ->add();
    }
}
