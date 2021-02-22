<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления свойства "Фото большое" и "Фото маленькое" в ИБ "Торговые предложения ЮБИ"
 * Class UpdateJewelrySkuProperties20200528151213766689
 */
class UpdateJewelrySkuProperties20200528151213766689 extends BitrixMigration
{
    /**
     * Проставляет флаг обязательности свойства
     *
     * @param bool $required Необходимость
     *
     * @return void
     */
    private function update(bool $required): void
    {
        $property = new Property(JewelrySku::iblockID());
        $property->addPropertyToQuery('PHOTO_SMALL');
        $property->addPropertyToQuery('PHOTO_BIG');
        $propertiesInfo = $property->getPropertiesInfo();

        foreach ($propertiesInfo as $propertyInfo) {
            (new CIBlockProperty())->Update(
                $propertyInfo['PROPERTY_ID'],
                ['IS_REQUIRED' => $required ? 'Y' : 'N']
            );
        }
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(false);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(true);
    }
}
