<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlock;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;

class AddIblockDeliveryTime20190429122311038556 extends BitrixMigration
{
    /**
     * ID типа инфоблоков
     */
    const CODE = 'delivery_time';
    
    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $factory = (new IBlock())
            ->constructDefault('Время доставки', self::CODE, 'checkout')
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(100);
        
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
