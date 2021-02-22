<?php

use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;
use Arrilot\BitrixMigrations\Exceptions\MigrationException;
use App\Models\Banners\LegalBanner;

class AddBanners20201019142503252059 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        LegalBanner::create([
            'NAME' => 'Главная - бриллианты от лидера в добыче алмазов',
            'CODE' => 'index_jewerlly',
            'ACTIVE' => 'N',
            'PROPERTY_VALUES' => [
                'TITLE_RU' => 'БРИЛЛИАНТЫ ОТ ЛИДЕРА В ДОБЫЧЕ АЛМАЗОВ',
                'TITLE_EN' => 'Diamonds from the worlds leading mining company',
                'TITLE_CN' => '来自领先世界公司的钻石'
            ]
        ]);
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
