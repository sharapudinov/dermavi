<?php

use App\Core\BitrixProperty\Property;
use App\Models\HL\DiamondOrder;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для изменения свойства "Прикрепленный файл" в таблице "Заказы на пр-во бриллиантов"
 * Class UpdateDiamondOrderTableFileProperty20200227125633167575
 */
class UpdateDiamondOrderTableFileProperty20200227125633167575 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $propertyCode = 'UF_FILE';

        $properties = Property::getUserFields(
            DiamondOrder::tableName(),
            [$propertyCode]
        );

        foreach ($properties as $property) {
            UserField::delete($property['ID']);
        }

        $entityId = 'HLBLOCK_' . highloadblock(DiamondOrder::tableName())['ID'];

        (new UserField())->constructDefault($entityId, $propertyCode)
            ->setXmlId($propertyCode)
            ->setMultiple(true)
            ->setLangDefault('ru', 'Файлы')
            ->setLangDefault('en', 'Files')
            ->setLangDefault('cn', 'Files')
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
        //
    }
}
