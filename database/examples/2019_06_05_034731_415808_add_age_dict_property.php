<?php

use App\Models\Catalog\Diamond;
use App\Models\Catalog\HL\DiamondAge;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Добавляение свойства Вариант возраста в бриллиант.
 *
 * Class AddAgeDictProperty20190605034731415808
 */
class AddAgeDictProperty20190605034731415808 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up(): void
    {
        $iblockId = Diamond::iblockID();

        (new IBlockProperty())->constructDefault('AGE_VARIANT', 'Вариант возраста', $iblockId)
            ->setSort(700)
            ->setPropertyTypeHl(DiamondAge::TABLE_CODE)
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down(): void
    {
        $this->deleteIblockElementPropertyByCode(Diamond::iblockID(), 'AGE_VARIANT');
    }
}
