<?php

use App\Core\BitrixProperty\Property;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления свойства "Видео" в ИБ "Торговые предложения ювелирных изделий"
 * Class UpdateJewelrySkuVideoProperty20200521130845742425
 */
class UpdateJewelrySkuVideoProperty20200521130845742425 extends BitrixMigration
{
    /**
     * Обновляет свойство
     *
     * @param string $newCode Новый символьный код свойства
     * @param string $oldCode Старый символьный код свойства
     * @param bool $multiple Флаг, указывающий множественность свойства
     *
     * @return void
     */
    private function update(string $newCode, string $oldCode, bool $multiple): void
    {
        $property = new Property(JewelrySku::iblockID());
        $property->addPropertyToQuery($oldCode);
        $propertyInfo = $property->getPropertiesInfo()[$oldCode];

        (new CIBlockProperty())->Update(
            $propertyInfo['PROPERTY_ID'],
            [
                'CODE' => $newCode,
                'MULTIPLE' => $multiple ? 'Y' : 'N'
            ]
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
        $this->update('VIDEO', 'VIDEOS', false);
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->update('VIDEO', 'VIDEOS', true);
    }

}
