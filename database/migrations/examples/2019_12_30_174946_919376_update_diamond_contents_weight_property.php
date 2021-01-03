<?php

use App\Models\Jewelry\JewelryDiamond;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для изменения свойства "Вес" в хлблоке "Бриллиант-вставка" с string на int
 * Class UpdateDiamondContentsWeightProperty20191230174946919376
 */
class UpdateDiamondContentsWeightProperty20191230174946919376 extends BitrixMigration
{
    /** @var string $entity Идентификатор сущности хлблока */
    private $entity;

    /**
     * UpdateDiamondContentsWeightProperty20191230174946919376 constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->entity = 'HLBLOCK_' . highloadblock(JewelryDiamond::TABLE_CODE)['ID'];

        /** @var array|mixed[] $property Массив, описывающий пользовательское свойство */
        $property = CUserTypeEntity::GetList([], ['ENTITY_ID' => $this->entity, 'FIELD_NAME' => 'UF_WEIGHT'])->Fetch();
        UserField::delete($property['ID']);
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new UserField())->constructDefault($this->entity, 'UF_WEIGHT')
            ->setUserType('double')
            ->setLangDefault('ru', 'Вес')
            ->setLangDefault('en', 'Weight')
            ->setLangDefault('cn', 'Weight')
            ->setSettings([
                'PRECISION' => 2
            ])
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
        (new UserField())->constructDefault($this->entity, 'UF_WEIGHT')
            ->setUserType('string')
            ->setLangDefault('ru', 'Вес')
            ->setLangDefault('en', 'Weight')
            ->setLangDefault('cn', 'Weight')
            ->add();
    }
}
