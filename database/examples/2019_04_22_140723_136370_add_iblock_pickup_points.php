<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\IBlockPropertyEnum;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use Arrilot\BitrixMigrations\Constructors\IBlock;

/**
 * Class AddIblockPickupPoints20190422140723136370
 */
class AddIblockPickupPoints20190422140723136370 extends BitrixMigration
{
    /**
     * ID типа инфоблоков
     */
    const CODE = 'pickup_point';
    
    /**
     * Run the migration.
     * @throws Exception
     */
    public function up()
    {
        $factory = (new IBlock())
            ->constructDefault('Пункты самовывоза', self::CODE, 'checkout')
            ->setVersion(2)
            ->setIndexElement(false)
            ->setIndexSection(false)
            ->setWorkflow(false)
            ->setBizProc(false)
            ->setSort(600);
    
        $factory->fields['LID'] = ['s1', 's2', 's3'];
        $factory->fields['GROUP_ID'] = [1 => 'X', 2 => 'R'];
    
        $iblockId = $factory->add();
    
        (new IBlockProperty())
            ->constructDefault('NAME_RU', 'Наименование (рус)', $iblockId)
            ->setSort(100)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('NAME_EN', 'Наименование (англ)', $iblockId)
            ->setSort(120)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('NAME_CN', 'Наименование (кит)', $iblockId)
            ->setSort(130)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('GOOGLE_MAP', 'Карта', $iblockId)
            ->setIsRequired()
            ->setPropertyType('S')
            ->setUserType('map_google')
            ->setSort(200)
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('CITY_RU', 'Город (рус)', $iblockId)
            ->setSort(300)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('CITY_EN', 'Город (англ)', $iblockId)
            ->setSort(320)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('CITY_CN', 'Город (кит)', $iblockId)
            ->setSort(330)
            ->setIsRequired()
            ->add();
        
        (new IBlockProperty())
            ->constructDefault('ADDRESS_RU', 'Адрес (рус)', $iblockId)
            ->setSort(400)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('ADDRESS_EN', 'Адрес (англ)', $iblockId)
            ->setSort(420)
            ->setIsRequired()
            ->add();
    
        (new IBlockProperty())
            ->constructDefault('ADDRESS_CN', 'Адрес (кит)', $iblockId)
            ->setSort(430)
            ->setIsRequired()
            ->add();
        
        (new IBlockProperty())
            ->constructDefault('PHONES', 'Телефоны', $iblockId)
            ->setSort(500)
            ->setIsRequired()
            ->setMultiple(true)
            ->add();
        
        (new IBlockProperty())
            ->constructDefault('WORKING_HOURS_RU', 'Время работы (рус)', $iblockId)
            ->setSort(600)
            ->add();
        
        (new IBlockProperty())
            ->constructDefault('WORKING_HOURS_EN', 'Время работы (англ)', $iblockId)
            ->setSort(610)
            ->setIsRequired()
            ->add();
        
        (new IBlockProperty())
            ->constructDefault('WORKING_HOURS_CN', 'Время работы (кит)', $iblockId)
            ->setSort(620)
            ->add();
        
        $propertyUserEntityTypeId = (new IBlockProperty())
            ->constructDefault('USER_ENTITY_TYPE', 'Тип пользователя', $iblockId)
            ->setIsRequired()
            ->setPropertyType('L')
            ->setSort(700)
            ->setMultiple(true)
            ->add();
        (new IBlockPropertyEnum())
            ->constructDefault('LEGAL_ENTITY', 'Юридическое лицо', $propertyUserEntityTypeId)
            ->add();
        (new IBlockPropertyEnum())
            ->constructDefault('PHYSICAL_ENTITY', 'Физическое лицо', $propertyUserEntityTypeId)
            ->add();
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
