<?php

use App\Helpers\SiteHelper;
use App\Models\Main\MainScreenVideo;
use Arrilot\BitrixMigrations\BaseMigrations\BitrixMigration;

/**
 * Класс, описывающий миграцию для создания инфоблока "Видео для главного экрана"
 * Class AddMainScreenVideoIblock20191212103305940763
 */
class AddMainScreenVideoIblock20191212103305940763 extends BitrixMigration
{
    /**
     * Run the migration.
     *
     * @return mixed
     * @throws \Exception
     */
    public function up()
    {
        (new CIBlock())->Add([
            'ACTIVE' => 'Y',
            'NAME' => 'Видео для главного экрана',
            'CODE' => MainScreenVideo::IBLOCK_CODE,
            'IBLOCK_TYPE_ID' => 'main',
            'SITE_ID' => SiteHelper::getSites()->pluck('LID')->toArray(),
            'GROUP_ID' => [1 => 'W', 2 => 'R']
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Видео (англ)',
            'ACTIVE' => 'Y',
            'CODE' => 'VIDEO_EN',
            'SORT' => '1',
            'IS_REQUIRED' => 'Y',
            'PROPERTY_TYPE' => 'F',
            'IBLOCK_ID' => MainScreenVideo::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Видео (рус)',
            'ACTIVE' => 'Y',
            'CODE' => 'VIDEO_RU',
            'SORT' => '2',
            'PROPERTY_TYPE' => 'F',
            'IBLOCK_ID' => MainScreenVideo::iblockId()
        ]);

        (new CIBlockProperty())->Add([
            'NAME' => 'Видео (кит)',
            'ACTIVE' => 'Y',
            'CODE' => 'VIDEO_CN',
            'SORT' => '3',
            'PROPERTY_TYPE' => 'F',
            'IBLOCK_ID' => MainScreenVideo::iblockId()
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
        CIBlock::Delete(MainScreenVideo::iblockId());
    }
}
