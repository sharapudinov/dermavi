<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use CIBlockProperty;

class SetArticlePropertySingle20200408112856286446 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $iblockId = \App\Models\Blog\Article::iblockID();
        $propertyID = $this->getIblockPropIdByCode('GRID_SECTION', $iblockId);

        (new CIBlockProperty)->Update($propertyID, [
            'MULTIPLE' => 'N',
        ]);

    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $iblockId = \App\Models\Blog\Article::iblockID();
        $propertyID = $this->getIblockPropIdByCode('GRID_SECTION', $iblockId);

        (new CIBlockProperty)->Update($propertyID, [
            'MULTIPLE' => 'Y',
        ]);
    }
}
