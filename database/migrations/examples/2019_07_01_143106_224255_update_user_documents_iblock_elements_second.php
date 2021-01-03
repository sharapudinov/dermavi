<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class UpdateUserDocumentsIblockElementsSecond20190701143106224255 extends BitrixMigration
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
        $elementId = 11885;
        CIBlockElement::SetPropertyValues(
            $elementId,
            $iblockId,
            'Копия паспорта: первые страницы и страницы регистрации',
            'NAME_RU'
        );
        CIBlockElement::SetPropertyValues(
            $elementId,
            $iblockId,
            'Passport copy: first pages and registration pages',
            'NAME_EN'
        );
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
        $elementId = 11885;
        CIBlockElement::SetPropertyValues(
            $elementId,
            $iblockId,
            'Копия паспорта: первой страницы и страницы регистрации',
            'NAME_RU'
        );
        CIBlockElement::SetPropertyValues(
            $elementId,
            $iblockId,
            'Copy of passport: first page and registration page',
            'NAME_EN'
        );
    }
}
