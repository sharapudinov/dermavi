<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use App\Models\Catalog\Catalog;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddDiamondNameFieldForDiamond20200131150332654569 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = Diamond::iblockID();
        (new IBlockProperty())->constructDefault('DIAMOND_NAME', 'Имя бриллианта', $iblockId)->add();
        (new IBlockProperty())->constructDefault('TRACING_MESSAGE', 'Сообщение для трейсинга', $iblockId)->add();

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => Diamond::iblockID(), 'CODE' => 'DIAMOND_NAME'])->Fetch();
        CIBlockProperty::Delete($property['ID']);
        $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => Diamond::iblockID(), 'CODE' => 'TRACING_MESSAGE'])->Fetch();
        CIBlockProperty::Delete($property['ID']);

    }
}
