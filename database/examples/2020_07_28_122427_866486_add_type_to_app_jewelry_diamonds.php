<?php

use Arrilot\BitrixIblockHelper\HLblock;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\Constructor;
use Arrilot\BitrixMigrations\Constructors\UserField;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddTypeToAppJewelryDiamonds20200728122427866486 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $hlId = HLblock::getByTableName('app_jewelry_diamonds')['ID'];

        (new UserField)
            ->constructDefault(Constructor::objHLBlock($hlId), 'UF_TYPE')
            ->setLangDefault('ru', 'Тип камня')
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
        $hlId = HLblock::getByTableName('app_jewelry_diamonds')['ID'];
        (new CUserTypeEntity)->Delete($this->getUFIdByCode(Constructor::objHLBlock($hlId), 'UF_TYPE'));
    }
}
