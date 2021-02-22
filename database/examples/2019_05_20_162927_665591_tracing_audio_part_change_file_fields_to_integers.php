<?php

use App\Helpers\LanguageHelper;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use CUserTypeEntity;

class TracingAudioPartChangeFileFieldsToIntegers20190520162927665591 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock('tracing_audio_part')['ID'];

        $langs = LanguageHelper::getAvailableLanguages();
        foreach ($langs as $language) {
            $language = strtoupper($language);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_AUDIO_' . $language]
            )->Fetch();
            if ($field['ID']) {
                UserField::delete($field['ID']);
            }
        }

        (new UserField())->constructDefault($entityId, 'AUDIO_RU')
            ->setXmlId('UF_AUDIO_RU')
            ->setLangDefault('ru', 'Аудио (рус)')
            ->setLangDefault('en', 'Audio (ru)')
            ->setLangDefault('cn', 'Audio (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'AUDIO_EN')
            ->setXmlId('UF_AUDIO_RU')
            ->setLangDefault('ru', 'Аудио (англ)')
            ->setLangDefault('en', 'Audio (en)')
            ->setLangDefault('cn', 'Audio (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'AUDIO_CN')
            ->setXmlId('UF_AUDIO_CN')
            ->setLangDefault('ru', 'Аудио (кит)')
            ->setLangDefault('en', 'Audio (cn)')
            ->setLangDefault('cn', 'Audio (cn)')
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
        $entityId = 'HLBLOCK_' . highloadblock('tracing_audio_part')['ID'];

        $langs = LanguageHelper::getAvailableLanguages();
        foreach ($langs as $language) {
            $language = strtoupper($language);

            $field = CUserTypeEntity::GetList(
                [],
                ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_AUDIO_' . $language]
            )->Fetch();
            UserField::delete($field['ID']);
        }

        (new UserField())->constructDefault($entityId, 'AUDIO_RU')
            ->setXmlId('UF_AUDIO_RU')
            ->setUserType('file')
            ->setLangDefault('ru', 'Аудио (рус)')
            ->setLangDefault('en', 'Audio (ru)')
            ->setLangDefault('cn', 'Audio (ru)')
            ->add();

        (new UserField())->constructDefault($entityId, 'AUDIO_EN')
            ->setXmlId('UF_AUDIO_RU')
            ->setUserType('file')
            ->setLangDefault('ru', 'Аудио (англ)')
            ->setLangDefault('en', 'Audio (en)')
            ->setLangDefault('cn', 'Audio (en)')
            ->add();

        (new UserField())->constructDefault($entityId, 'AUDIO_CN')
            ->setXmlId('UF_AUDIO_CN')
            ->setUserType('file')
            ->setLangDefault('ru', 'Аудио (кит)')
            ->setLangDefault('en', 'Audio (cn)')
            ->setLangDefault('cn', 'Audio (cn)')
            ->add();
    }
}
