<?php

use App\Models\Main\PromoBanner;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для обновления контента в промо-баннере "Leading in social activities"
 * Class UpdateLeadingInSocialActivitiesBanner20200219181037814862
 */
class UpdateLeadingInSocialActivitiesBanner20200219181037814862 extends BitrixMigration
{
    /**
     * Обновляет контент в промо-баннере
     *
     * @param string $symbol Символ рубля
     *
     * @return void
     */
    private function updateProperty(string $symbol)
    {
        $promoBanner = PromoBanner::first();
        $promoBanner->update([
            'PROPERTY_INFOGRAPHICS_TEXTS_RU_VALUE' => [
                '542 843 624 ' . $symbol,
                '466 002 670 ' . $symbol,
                '1 161 411 797 ' . $symbol
            ]
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
        $this->updateProperty('<span class="rub">б</span>');
    }

    /**
     * Reverse the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function down()
    {
        $this->updateProperty('₽');
    }
}
