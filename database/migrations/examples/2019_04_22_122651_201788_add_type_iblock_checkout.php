<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockType;

/**
 * Class AddTypeIblockCheckout20190422122651201788
 */
class AddTypeIblockCheckout20190422122651201788 extends BitrixMigration
{
    /**
     * ID типа инфоблоков
     */
    const ID = 'checkout';
    
    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        (new IBlockType())
            ->setId(self::ID)
            ->setSections()
            ->setInRss()
            ->setLang('ru', 'Оформление заказа')
            ->setLang('en', 'Checkout')
            ->setLang('cn', '制定订单')
            ->add();
    }
    
    /**
     * Reverse the migration.
     * @throws Exception
     */
    public function down()
    {
        (new IBlockType())
            ->delete(self::ID);
    }
}
