<?php

use App\Models\Catalog\Diamond;
use App\Models\Catalog\HL\DiamondPacket;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Добавляет поля цены для физических лиц в HL пакетов и ИБ бриллиантов.
 *
 * Class AddDiamondIndividualPrice20190625040927696284
 */
class AddDiamondIndividualPrice20190625040927696284 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @throws \Exception
     */
    public function up(): void
    {
        $entityId = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
        (new UserField())->constructDefault($entityId, 'UF_PUBLIC_COST')
            ->setUserType('double')
            ->setSettings(['PRECISION' => 2, 'MIN_VALUE' => 0])
            ->setLangDefault('ru', 'Цена для физ. лиц')
            ->setLangDefault('en', 'Public cost')
            ->add();

        (new IBlockProperty())->constructDefault('PUBLIC_PRICE', 'Цена для физ. лиц', Diamond::iblockID())
            ->setPropertyType('N')
            ->add();
    }

    /**
     * Reverse the migration.
     *
     * @throws \Exception
     */
    public function down(): void
    {
        $entityId = 'HLBLOCK_' . highloadblock(DiamondPacket::TABLE_NAME)['ID'];
        $fieldId = $this->getUFIdByCode($entityId, 'UF_PUBLIC_COST');
        (new CUserTypeEntity())->Delete($fieldId);

        $this->deleteIblockElementPropertyByCode(Diamond::iblockID(), 'PUBLIC_PRICE');
    }
}
