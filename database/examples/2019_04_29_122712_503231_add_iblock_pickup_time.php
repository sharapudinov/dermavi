<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlock;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockPickupTime20190429122712503231 extends BitrixMigration
{
    /**
     * ID типа инфоблоков
     */
    const CODE = 'pickup_time';
    
    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $factory = (new IBlock())
            ->constructDefault('Время самовывоза', self::CODE, 'checkout')
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(110);
        
        $factory->fields['LID'] = ['s1', 's2', 's3'];
        $factory->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];
        
        $factory->add();
    }
    
    /**
     * Reverse the migration.
     * @throws MigrationException
     */
    public function down()
    {
        $this->deleteIblockByCode(self::CODE);
    }
}
