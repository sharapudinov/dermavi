<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\OrderPropsTable;

/**
 * Class UpdateOrderRequiredProperties20190426125818007808
 */
class UpdateOrderRequiredProperties20190426125818007808 extends BitrixMigration
{
    /**
     * @var bool
     */
    public $use_transaction = true;
    
    /**
     * Необязательные свойства
     */
    const NOT_REQUIRED_PROPERTIES = [
        'COMPANY_NAME',
        'CLIENT_NAME',
        'TAX_ID',
        'COUNTRY',
        'EMAIL',
    ];
    
    /**
     * UpdateOrderRequiredProperties20190426125818007808 constructor.
     * @throws Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        parent::__construct();
        
        Loader::includeModule('sale');
    }
    
    /**
     * Run the migration.
     * @throws MigrationException
     */
    public function up()
    {
        $rs = OrderPropsTable::getList([
            'filter' => ['CODE' => static::NOT_REQUIRED_PROPERTIES]
        ]);
    
        while ($prop = $rs->fetch()) {
            $res = OrderPropsTable::update($prop['ID'], [
                'REQUIRED' => 'N',
            ]);
            if (!$res->isSuccess()) {
                throw new MigrationException("Не удалось снять обязательность для свойства '{$prop['CODE']}'. ".implode("\n", $res->getErrorMessages()));
            }
        }
    }

    /**
     * Reverse the migration.
     * @throws MigrationException
     */
    public function down()
    {
        $rs = OrderPropsTable::getList([
            'filter' => ['CODE' => static::NOT_REQUIRED_PROPERTIES]
        ]);
    
        while ($prop = $rs->fetch()) {
            $res = OrderPropsTable::update($prop['ID'], [
                'REQUIRED' => 'Y',
            ]);
            if (!$res->isSuccess()) {
                throw new MigrationException("Не удалось установить обязательность для свойства '{$prop['CODE']}'. ".implode("\n", $res->getErrorMessages()));
            }
        }
    }
}
