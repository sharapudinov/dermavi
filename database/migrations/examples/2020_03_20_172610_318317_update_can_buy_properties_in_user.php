<?php

use App\Core\BitrixProperty\Property;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для обновления свойств "Доступна покупка..."
 * Class UpdateCanBuyPropertiesInUser20200320172610318317
 */
class UpdateCanBuyPropertiesInUser20200320172610318317 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|array[] $properties */
        $properties = Property::getPropertiesInfoFromUser(['UF_PURCHASE_OVER_100', 'UF_PURCHASE_OVER_600']);
        foreach ($properties as $code => $property) {
            UserField::delete($properties[$code]['PROPERTY_ID']);
        }

        (new UserField())->constructDefault('USER', 'UF_PURCHASE_OVER_100')
            ->setXmlId('UF_PURCHASE_OVER_100')
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Доступна покупка более 100 000')
            ->setLangDefault('en', 'Purchase available over 100 000')
            ->setLangDefault('cn', 'Purchase available over 100 000')
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
