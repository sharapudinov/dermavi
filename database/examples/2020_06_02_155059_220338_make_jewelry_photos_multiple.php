<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для того, чтобы сделать свойства "Фото большое" и "Фото маленькое" множественными в ЮБИ
 * Class MakeJewelryPhotosMultiple20200602155059220338
 */
class MakeJewelryPhotosMultiple20200602155059220338 extends BitrixMigration
{
    /**
     * Обновляет свойства
     *
     * @param bool $multiple Флаг множественности
     *
     * @return void
     */
    private function update(bool $multiple): void
    {
        $small = 'PHOTO_SMALL';
        $big = 'PHOTO_BIG';

        $property = new Property(JewelrySku::iblockID());
        $property->addPropertyToQuery($small);
        $property->addPropertyToQuery($big);
        $propertiesInfo = $property->getPropertiesInfo();

        (new CIBlockProperty())->Update(
            $propertiesInfo[$small]['PROPERTY_ID'],
            ['MULTIPLE' => $multiple ? 'Y' : 'N']
        );

        (new CIBlockProperty())->Update(
            $propertiesInfo[$big]['PROPERTY_ID'],
            ['MULTIPLE' => $multiple ? 'Y' : 'N']
        );
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(true);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(false);
    }
}
