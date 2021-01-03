<?php

namespace App\Feed;

use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

/**
 * Базовый класс генератора фида
 * @package App\Feed
 */
abstract class BaseGenerator
{
    /**
     * Путь к папке с фидами относительно корня публичной части сайта
     */
    public const FEED_DIRECTORY = '/upload/feed';

    /**
     * Возвращает абсолютный путь к файлу фида
     * @param string $fileName
     * @return string
     */
    public function getFilePath(string $fileName): string
    {
        $dirName = sprintf('%s%s', Application::getDocumentRoot(), static::FEED_DIRECTORY);

        if (!is_dir($dirName)) {
            Directory::createDirectory($dirName);
        }

        return sprintf('%s/%s', $dirName, $fileName);
    }

    /**
     * @param string $string
     * @param string $encoding
     * @return string
     */
    public function ucFirst(string $string, string $encoding = 'UTF-8'): string
    {
        $firstChar = mb_strtoupper(mb_substr($string, 0, 1, $encoding), $encoding);
        return $firstChar . mb_substr($string, 1, mb_strlen($string, $encoding), $encoding);
    }
}
