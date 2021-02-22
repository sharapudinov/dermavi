<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;
use App\Models\Catalog\HL\DiamondPacket;

class AddDiamondStockFieldIntoDiamondPacketTable20190806152229837013 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $entityId = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
        (new UserField())->constructDefault($entityId, 'UF_STOCK_ID')
            ->setUserType('string')
            ->setLangDefault('ru', 'Идентификатор стока')
            ->setLangDefault('en', 'Stock id')
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
        $entityId = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
        $field[] = CUserTypeEntity::GetList(
            [$by => $order],
            ['ENTITY_ID' => $entityId, 'FIELD_NAME' => 'UF_REGISTRATION_ID']
        )->Fetch();
        UserField::delete($field['ID']);
    }
}
