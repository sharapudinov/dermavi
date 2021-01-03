<?php

namespace App\Frontend;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;

class Frontend
{
    /**
     * Название куки куда запоминаем переключение.
     *
     * @var string
     */
    public $cookieName = 'frontend_build';

    /**
     * Директория где лежит dev-сборка.
     *
     * @var string
     */
    public $devDir = '/html/';

    /**
     * Директория где лежит production-сборка.
     *
     * @var string
     */
    public $productionDir = '/assets/build/';

    /**
     * Версия для инвалидации кэша.
     *
     * @var string
     */
    public $version;


    public function __construct()
    {
        $this->version = in_production_or_stage() ? $this->getVersionFromDB() : time();
    }

    /**
     * Включен ли режим отладки.
     *
     * @return bool
     */
    public function isInDevMode()
    {
        return !in_production() && isset($_COOKIE[$this->cookieName]) && $_COOKIE[$this->cookieName] === 'dev';
    }

    /**
     * Функиця для формирования нужного пути до ресурса из сборщика.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function path($path, $addVersion = false)
    {
        $resultPath = $this->isInDevMode()
            ? $this->devDir . $path
            : $this->productionDir . $path;

        if ($addVersion) {
            $separator = mb_strpos($resultPath, '?') ? '&' : '?';
            $resultPath .= $separator .'v=' . $this->version;
        }
        
        return $resultPath;
    }

    /**
     * Формирование пути до директории со скриптами.
     * Добавляет версию по-умолчанию.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function js($path, $addVersion = true)
    {
        return $this->path("js/$path", $addVersion);
    }

    /**
     * Формирование пути до директории со стилями.
     * Добавляет версию по-умолчанию.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function css($path, $addVersion = true)
    {
        return $this->path("css/$path", $addVersion);
    }

    /**
     * Формирование пути до директории с шрифтами.
     * Добавляет версию по-умолчанию.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function fonts($path, $addVersion = true)
    {
        return $this->path("fonts/$path", $addVersion);
    }

    /**
     * Формирование пути до директории с изображениями.
     * Не добавляет версию по-умолчанию.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function img($path, $addVersion = false)
    {
        return $this->path("img/$path", $addVersion);
    }
    
    /**
     * Формирование пути до директории с SVG.
     * Не добавляет версию по-умолчанию.
     *
     * @param string $path
     * @param bool $addVersion
     * @return string
     */
    public function svg($path, $addVersion = false)
    {
        return $this->path("img/svg/$path", $addVersion);
    }
    
    /**
     * Обновление версии фронтэнда до следующей.
     *
     * @return string
     */
    public function updateVersion()
    {
        $this->version = (string)((int) $this->getVersionFromDB() + 1);
        Option::set('main', 'frontend_version', (string) $this->version);

        // скидываем кэш b_option если он используется
        Application::getInstance()->getManagedCache()->clean('b_option');

        return $this->version;
    }

    /**
     * Получение версии фронтэнда из b_option.
     *
     * @return string
     */
    private function getVersionFromDB()
    {
        return (string) Option::get('main', 'frontend_version', 1000);
    }
}
