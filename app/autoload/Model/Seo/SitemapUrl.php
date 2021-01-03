<?php

namespace App\Models\Seo;

use App\Models\Auxiliary\HlD7Model;

class SitemapUrl extends HlD7Model
{
    /** @var string Название таблицы в БД */
    public const TABLE_CODE = 'app_sitemap_url';

    /**
     * Получает класс таблицы
     *
     * @return string
     */
    public static function tableClass()
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    public function getUrl()
    {
        return $this['UF_URL'];
    }

    public function getParentUrl()
    {
        return $this['UF_PARENT_URL'];
    }

    public function getPriority()
    {
        return $this['UF_PRIORITY'];
    }

    public function getChangefreq()
    {
        return $this['UF_CHANGEFREQ'];
    }
}