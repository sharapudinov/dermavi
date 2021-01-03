<?php

use App\Models\Catalog\HL\FormsAccordanceGroup;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/** @noinspection PhpUnused */

class FillAppFormsAccordancesGroups20201110185304524753 extends BitrixMigration
{
    public function up(): void
    {
        if (FormsAccordanceGroup::getList()->isNotEmpty()) {
            return;
        }

        collect(
            [
                'Овал' => [1005, 1116],
                'Маркиз' => [1007, 1112],
                'Груша' => [1006, 1113],
                'Круглый' => [1001, 1111],
                'Принцесса' => [1020, 1117],
                'Изумруд квадратный' => [1040, 1124],
                'Изумруд' => [1003, 1114],
            ]
        )->map(
            static function (array $formsId, string $formName) {
                if (!FormsAccordanceGroup::create(['UF_NAME' => $formName, 'UF_FORMS_ID' => $formsId])) {
                    throw new RuntimeException('Unable to save FormsAccordanceGroup');
                }
            }
        );
    }

    public function down(): void
    {
    }
}
