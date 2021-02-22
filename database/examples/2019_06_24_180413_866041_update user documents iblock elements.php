<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateUserDocumentsIblockElements20190624180413866041 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \App\Models\Client\PersonalSectionDocumentKind::iblockId();
        CIBlockElement::SetPropertyValues('9570', $iblockId, 'Properly executed power of attorney', 'NAME_EN');
        CIBlockElement::SetPropertyValues('9580', $iblockId, 'Constitutional documents', 'NAME_EN');
        CIBlockElement::SetPropertyValues('9566', $iblockId, 'Client questionnaire', 'NAME_EN');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = \App\Models\Client\PersonalSectionDocumentKind::iblockId();
        CIBlockElement::SetPropertyValues('9570', $iblockId, 'Properly executed power of attorney', 'NAME_EN');
        CIBlockElement::SetPropertyValues('9580', $iblockId, 'Учредительные документы', 'NAME_EN');
        CIBlockElement::SetPropertyValues('9566', $iblockId, 'Анкета клиента', 'NAME_EN');
    }
}
