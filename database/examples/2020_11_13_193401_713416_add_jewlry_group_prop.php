<?php

use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

class AddJewlryGroupProp20201113193401713416 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        //
        $iblockId = Jewelry::iblockID();
        (new IBlockProperty())->constructDefault('GROUP', 'Гарнитур', $iblockId)->add();
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        //
        $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => Jewelry::iblockID(), 'CODE' => 'GROUP'])->Fetch();
        CIBlockProperty::Delete($property['ID']);
    }
}
