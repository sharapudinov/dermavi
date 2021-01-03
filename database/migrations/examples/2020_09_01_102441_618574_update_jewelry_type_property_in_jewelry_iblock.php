<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\Jewelry;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для изменения типа свойства "Тип изделия" на множественное в ИБ "Ювелирные изделия"
 * Class UpdateJewelryTypePropertyInJewelryIblock20200901102441618574
 */
class UpdateJewelryTypePropertyInJewelryIblock20200901102441618574 extends BitrixMigration
{
    /** @var string Старый символьный код свойства */
    private const OLD_PROPERTY_CODE = 'JEWELRY_TYPE';

    /** @var string Новый символьный код свойства */
    private const NEW_PROPERTY_CODE = 'JEWELRY_TYPES';

    /**
     * Обновляет свойство
     *
     * @param string $oldCode Старый символьный код
     * @param string $newCode Новый символьный код
     * @param bool $multiple Флаг множественности свойства
     *
     * @return void
     */
    private function update(string $oldCode, string $newCode, bool $multiple): void
    {
        $property = new Property(Jewelry::iblockID());
        $property->addPropertyToQuery($oldCode);
        $propertyInfo = $property->getPropertiesInfo()[$oldCode];

        (new CIBlockProperty())->Update($propertyInfo['PROPERTY_ID'], [
            'CODE' => $newCode,
            'MULTIPLE' => $multiple ? 'Y' : 'N'
        ]);
    }

    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        $this->update(self::OLD_PROPERTY_CODE, self::NEW_PROPERTY_CODE, true);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update(self::NEW_PROPERTY_CODE, self::OLD_PROPERTY_CODE, false);
    }
}
