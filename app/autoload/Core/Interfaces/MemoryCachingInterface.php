<?php

namespace App\Core\Interfaces;

interface MemoryCachingInterface
{
    /**
     * Запись данных в хранилище
     *
     * @param string $key
     * @param mixed $var
     * @param null $flag
     * @param null $expire
     *
     * @return bool|void
     */
    public function set($key, $var, $flag = null, $expire = null);

    /**
     * Возвращает данные из хранилища
     *
     * @param array|string $key
     * @param null $flags
     *
     * @return array|mixed|string
     */
    public function get($key, &$flags = null);

    /**
     * Перезаписывает данные в хранилище
     *
     * @param string $key
     * @param mixed $var
     * @param null $flag
     * @param null $expire
     *
     * @return bool|void
     */
    public function replace($key, $var, $flag = null, $expire = null);

    /**
     * Удаляет данные из хранилища
     *
     * @param string $key
     * @param int $timeout
     *
     * @return bool|void
     */
    public function delete($key, $timeout = 0);
}
