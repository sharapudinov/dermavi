<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления таблицы "Бриллианты для заготовки"
 * Class UpdateAppJewelryBlankDiamondsTable20200616171756477461
 */
class UpdateAppJewelryBlankDiamondsTable20200616171756477461 extends BitrixMigration
{
    /** @var string $entityId Идентификатор сущности */
    private $entityId;

    /**
     * UpdateAppJewelryBlankDiamondsTable20200616171756477461 constructor.
     */
    public function __construct()
    {
        $this->entityId = 'HLBLOCK_' . highloadblock(JewelryBlankDiamonds::TABLE_CODE)['ID'];
        parent::__construct();
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $property = Property::getUserFields(JewelryBlankDiamonds::TABLE_CODE, ['UF_DIAMOND_ID'])[0];
        UserField::delete($property['ID']);

        (new UserField())->constructDefault($this->entityId, 'UF_DIAMONDS_ID')
            ->setXmlId('UF_DIAMONDS_ID')
            ->setMultiple(true)
            ->setLangDefault('ru', 'Идентификаторы бриллианта')
            ->setLangDefault('en', 'Идентификаторы бриллианта')
            ->setLangDefault('cn', 'Идентификаторы бриллианта')
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
        $property = Property::getUserFields(JewelryBlankDiamonds::TABLE_CODE, ['UF_DIAMONDS_ID'])[0];
        UserField::delete($property['ID']);

        (new UserField())->constructDefault($this->entityId, 'UF_DIAMOND_ID')
            ->setXmlId('UF_DIAMOND_ID')
            ->setLangDefault('ru', 'Идентификатор бриллианта')
            ->setLangDefault('en', 'Идентификатор бриллианта')
            ->setLangDefault('cn', 'Идентификатор бриллианта')
            ->add();
    }
}
