<?php

use App\Core\BitrixProperty\Property;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\UserField;

/**
 * Класс, описывающий миграцию для изменения свойств "Доступна покупка" + сумма
 * Class DeletePurchaseUp600PropertyFromUser20200302171942083668
 */
class DeletePurchaseUp600PropertyFromUser20200302171942083668 extends BitrixMigration
{
    /** @var string Символьный код свойства "Доступна покупка до 600" */
    private const UP_TO_600 = 'UF_PURCHASE_UP_600';

    /** @var string Символьный код нового свойства */
    private const PURCHASE_OVER_100 = 'UF_PURCHASE_OVER_100';

    /** @var string Символьный код старого */
    private const PURCHASE_OVER_600 = 'UF_PURCHASE_OVER_600';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        /** @var array|array[] $properties */
        $properties = Property::getPropertiesInfoFromUser([self::UP_TO_600, self::PURCHASE_OVER_600]);

        UserField::delete($properties[self::UP_TO_600]['PROPERTY_ID']);

        (new UserField())->constructDefault('USER', self::PURCHASE_OVER_100)
            ->setXmlId(self::PURCHASE_OVER_100)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Доступна покупка более 100 000')
            ->setLangDefault('en', 'Purchase available over 100 000')
            ->setLangDefault('cn', 'Purchase available over 100 000')
            ->update($properties[self::PURCHASE_OVER_600]['PROPERTY_ID']);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        /** @var array|array[] $properties */
        $properties = Property::getPropertiesInfoFromUser([self::PURCHASE_OVER_100]);

        (new UserField())->constructDefault('USER', self::UP_TO_600)
            ->setXmlId(self::UP_TO_600)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Доступна покупка до 600 000')
            ->setLangDefault('en', 'Purchase available up to 600 000')
            ->setLangDefault('cn', 'Purchase available up to 600 000')
            ->add();

        (new UserField())->constructDefault('USER', self::PURCHASE_OVER_600)
            ->setXmlId(self::PURCHASE_OVER_600)
            ->setUserType('boolean')
            ->setLangDefault('ru', 'Доступна покупка более 600 000')
            ->setLangDefault('en', 'Purchase available over 600 000')
            ->setLangDefault('cn', 'Purchase available over 600 000')
            ->update($properties[self::PURCHASE_OVER_100]['PROPERTY_ID']);
    }
}
