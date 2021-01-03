<?php

namespace App\Core\Memcache;

use App\Core\Interfaces\MemoryCachingInterface;
use App\Core\System\SingletonTrait;
use Memcache;

/**
 * Класс, описывающий логику взаимодействия с memcache
 * Class MemcacheInitializer
 *
 * @package App\Core\Memcache
 */
class MemcacheInitializer extends Memcache implements MemoryCachingInterface
{
    /** Трейт, описывающий синглтон */
    use SingletonTrait;

    /**
     * MemcacheInitializer constructor.
     */
    protected function __construct()
    {
        $settings = config('cache.memcache');
        if (!$this->connect($settings['host'], $settings['port'])) {
            logger('common')->error('Не удалось присоединиться к memcache');
        }
    }

    /**
     * Записывает данные в memcache
     *
     * @param string $key
     * @param mixed $var
     * @param null $flag
     * @param null $expire
     *
     * @return bool|void
     */
    public function set($key, $var, $flag = null, $expire = null)
    {
        parent::set($key, $var, $flag, $expire);
    }

    /**
     * Возвращает данные из memcache
     *
     * @param array|string $key
     * @param null $flags
     *
     * @return array|mixed|string
     */
    public function get($key, &$flags = null)
    {
        return parent::get($key, $flags);
    }

    /**
     * Перезаписывает данные в memcache
     *
     * @param string $key
     * @param mixed $var
     * @param null $flag
     * @param null $expire
     *
     * @return bool|void
     */
    public function replace($key, $var, $flag = null, $expire = null)
    {
        parent::replace($key, $var, $flag, $expire);
    }

    /**
     * Удаляет данные из memcache
     *
     * @param string $key
     * @param int $timeout
     *
     * @return bool|void
     */
    public function delete($key, $timeout = 0)
    {
        parent::delete($key, $timeout);
    }
}
