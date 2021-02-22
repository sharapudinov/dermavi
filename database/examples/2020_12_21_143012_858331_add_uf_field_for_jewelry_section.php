<?php

use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddUfFieldForJewelrySection20201221143012858331 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function up()
    {
        $entityId = 'IBLOCK_' . Jewelry::iblockID() . '_SECTION';
        $propertyCode = 'UF_ENGRAVING_DISABLED';

        (new UserField())->constructDefault($entityId, $propertyCode)
                         ->setXmlId($propertyCode)
                         ->setUserType('boolean')
                         ->setLangDefault('ru', 'Гравировка отключена')
                         ->setLangDefault('en', 'Engraving disabled')
                         ->setLangDefault('cn', 'Engraving disabled')
                         ->add();
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     * @return mixed
     */
    public function down()
    {
        //
    }
}
