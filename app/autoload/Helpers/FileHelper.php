<?php

namespace App\Helpers;

use CFile;
use SplFileObject;

/**
 * Класс-хелпер для работы с файлами
 * Class FileSizeHelper
 * @package App\Helpers
 */
class FileHelper
{
    /** @var array|null - Массив обозначений объема на разных языках (б, кб, мб, b, kb, mb) */
    private static $sizeTypes = [
        'en' => [
            'byte' => 'B',
            'kilobyte' => 'KB',
            'megabyte' => 'MB'
        ],
        'ru' => [
            'byte' => 'Б',
            'kilobyte' => 'Кб',
            'megabyte' => 'Мб'
        ]
    ];

    /**
     * Получаем объем файла
     *
     * @param $fileId - Идентификатор файла
     * @return string
     */
    public static function getFileSize(int $fileId): string
    {
        $fileSize = CFile::GetByID($fileId)->Fetch()['FILE_SIZE'];
        $fileSizeTypeVersion = LanguageHelper::getLanguageVersion() == 'ru' ? 'ru' : 'en';
        $ext = self::$sizeTypes[$fileSizeTypeVersion]['byte'];
        if ($fileSize > 1024) {
            $fileSize /= 1024;
            $ext = self::$sizeTypes[$fileSizeTypeVersion]['kilobyte'];
        }
        if ($fileSize > 1024) {
            $fileSize /= 1024;
            $ext = self::$sizeTypes[$fileSizeTypeVersion]['megabyte'];
        }

        return number_format($fileSize, 1, '.', ' ') . ' ' . $ext;
    }

    /**
     * Генерирует массив байтов на основе файла
     *
     * @param string $filePath - Путь до файла относительно upload/..
     * @return array
     */
    public static function createByteArray(string $filePath): array
    {
        $file = new SplFileObject(realpath($_SERVER['DOCUMENT_ROOT'] . '/../../' . $filePath));
        $string = '';
        while (false !== ($char = $file->fgetc())) {
            $string .= $char;
        }

        return unpack('C*', $string);
    }

    /**
     * Возвращает название файла без расширения
     *
     * @param string $file Название файла
     *
     * @return string
     */
    public static function getFileNameWithoutExtension(string $file): string
    {
        return explode('.', $file)[0];
    }

    /**
     * Возвращает массив файлов в указанной директории (удаляя . и ..)
     *
     * @param string $directory Путь до директории
     *
     * @return array|string[]
     */
    public static function getFilesFromDirectory(string $directory): array
    {
        return array_filter(scandir($directory), function (string $item) {
            return $item != '.' && $item != '..' && $item != 'Thumbs.db';
        });
    }
}
