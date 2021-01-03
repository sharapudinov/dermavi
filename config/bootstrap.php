<?php

use Arrilot\BitrixCacher\Cache;
use Arrilot\DotEnv\DotEnv;

define('PROJECT_PATH', realpath(__DIR__ . "/../"));

function project_path($path = '')
{
    return PROJECT_PATH . '/' . $path;
}

function public_path($path = '')
{
    return project_path("public/$path");
}

function app_path($path = '')
{
    return project_path("app/$path");
}

function local_path($path = '')
{
    return project_path("app/local/$path");
}

function logs_path($path = '')
{
    return project_path("logs/$path");
}

/**
 * @param         $key
 * @param         $minutes
 * @param Closure $callback
 * @param string  $initDir
 * @param string  $basedir
 *
 * @return \Arrilot\BitrixCacher\CacheBuilder|mixed
 */
function cache($key = null, $minutes = null, ?Callable $callback = null, $initDir = '/', $basedir = 'cache')
{
    if (func_num_args() === 0) {
        return new \Arrilot\BitrixCacher\CacheBuilder();
    }

    return Cache::remember($key, $minutes, Closure::fromCallable($callback), $initDir, $basedir);
}

function env($key, $default = null)
{
    return DotEnv::get($key, $default);
}

/**
 * Получение значения из конфига.
 *
 * config('foo') -> вернёт ['foo']['value'] из .settings_extra.php
 * config('foo.bar') -> в ['foo']['value']['bar']
 * config('foo.bar.baz') -> в ['foo']['value']['bar.baz']
 *
 * @param  string  $key
 * @param  mixed  $default
 * @return mixed
 */
function config($key, $default = null)
{
    $keyParts = explode('.', $key);
    $keyPartsCount = count($keyParts);
    $values = Bitrix\Main\Config\Configuration::getValue($keyParts[0]);
    if ($keyPartsCount < 2) {
        $value = $values;
    } elseif ($keyPartsCount === 2) {
        $value = $values[$keyParts[1]] ?? null;
    } else {
        $keyPartsWithoutFirst = [];
        foreach ($keyParts as $i => $part) {
            if ($i === 0) {
                continue;
            }
            $keyPartsWithoutFirst[] = $part;
        }
        $value = $values[implode('.', $keyPartsWithoutFirst)] ?? null;
    }

    return is_null($value) ? $default : $value;
}

require_once project_path('vendor/autoload.php');

(new \App\Core\System\LanguageSetter)();

DotEnv::load(project_path('config/.env.php'));
