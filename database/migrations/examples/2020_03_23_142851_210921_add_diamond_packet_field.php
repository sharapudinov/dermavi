<?php

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\HL\DiamondPacket;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для добавления поля "Вес GIA" в таблицу diamond_packet
 * Class AddDiamondPacketField20200323142851210921
 */
class AddDiamondPacketField20200323142851210921 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'UF_PACKET_WEIGHT_GIA';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entity = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
        (new UserField())->constructDefault($entity, self::PROPERTY_CODE)
            ->setXmlId(self::PROPERTY_CODE)
            ->setLangDefault('ru', 'Масса пакета (GIA)')
            ->setLangDefault('en', 'Weight (GIA)')
            ->setLangDefault('cn', 'Weight (GIA)')
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
        $property = Property::getUserFields(DiamondPacket::TABLE_NAME, [self::PROPERTY_CODE])[0];
        UserField::delete($property['ID']);
    }
}
