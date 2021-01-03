<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use App\Models\Jewelry\JewelryBlank;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Constructors\IBlockProperty;

/**
 * Класс, описывающий миграцию для добавления свойства "Стиль (ассистент по стилю)" в ИБ "Заготовка ЮБИ"
 * Class AddJewelryAssistantStyleToBlanksIblock20200629154651810530
 */
class AddJewelryAssistantStyleToBlanksIblock20200629154651810530 extends BitrixMigration
{
    /** @var string Символьный код свойства */
    private const PROPERTY_CODE = 'JEWELRY_ASSISTANT_STYLE';

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new IBlockProperty())
            ->constructDefault(
                self::PROPERTY_CODE,
                'Стиль (ассистент по стилю)',
                JewelryBlank::iblockID()
            )
            ->setUserType('directory')
            ->setUserTypeSettings([
                'TABLE_NAME' => JewelryAssistantStyle::TABLE_CODE,
            ])
            ->setSort(518)
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
        $property = new Property(JewelryBlank::iblockID());
        $property->addPropertyToQuery(self::PROPERTY_CODE);
        $propertyInfo = $property->getPropertiesInfo()[self::PROPERTY_CODE];
        IBlockProperty::delete($propertyInfo['PROPERTY_ID']);
    }
}
