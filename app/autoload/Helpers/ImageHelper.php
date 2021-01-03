<?php

namespace App\Helpers;

use \CFile;

/**
 * Класс-хелпер для работы с изображениями
 * Class ImageHelper
 * @package App\Helpers
 */
class ImageHelper
{
    /**
     * Получить высоту изображения
     *
     * @param string $path
     *
     * @return int|null
     */
    public static function getHeight(string $path): ?int
    {
        $imageSize = getimagesize($path);

        if ($imageSize) {
            return $imageSize[1];
        }

        return null;
    }

    /**
     * Получить ширину изображения
     *
     * @param string $path
     *
     * @return int|null
     */
    public static function getWidth(string $path): ?int
    {
        $imageSize = getimagesize($path);

        if ($imageSize) {
            return $imageSize[0];
        }

        return null;
    }

    /**
     * Конвертирует JPG, JPEG, PNG в WEBP
     *
     * @param $path
     *
     * @return string|null
     */
    public static function createWebp($path): ?string
    {
        // ALRSUP-1270 Проверяем, доступна ли нам функция imagewebp из библиотеки GD
        if (function_exists('imagewebp')) {
            // ALRSUP-1270 Формируем путь до WEBP версии на основании пути оригинального файла
            $webpPath = self::renameIntoWebp($path);

            // ALRSUP-1270 Проверяем, нет ли на диске уже сгенерированной WEBP-версии
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $webpPath)) {
                // ALRSUP-1270 Если файл уже есть, возвращаем путь до него
                return $webpPath;
            }

            // ALRSUP-1270 Получаем размер и формат оригинального изображения
            $info = getimagesize($_SERVER['DOCUMENT_ROOT'] . $path);

            // ALRSUP-1270 Проверяем, смогли ли получить формат оригинального изображения
            if ($info !== false && ($type = $info[2])) {
                // ALRSUP-1270 В зависимости от формата оригинала используем разные функции для конвертации
                switch ($type) {
                    case IMAGETYPE_JPEG:
                        $webpImg = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $path);
                        break;
                    case IMAGETYPE_PNG:
                        $webpImg = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $path);
                        break;
                }

                // ALRSUP-1270 Проверяем, удалась ли конвертация
                if ($webpImg) {
                    // ALRSUP-1270 Сохраняем WEBP версию
                    imagewebp($webpImg, $_SERVER['DOCUMENT_ROOT'] . $webpPath, 90);
                    // ALRSUP-1270 Освобождаем память
                    imagedestroy($webpImg);

                    // ALRSUP-1270 Возвращаем путь до WEBP версии
                    return $webpPath;
                }
            }
        }

        // ALRSUP-1270 Если не смогли получить WEBP изображение, возвращаем null
        return null;
    }

    /**
     * Переименовывает изображение в WEBP
     *
     * @param $path
     *
     * @return string|null
     */
    public static function renameIntoWebp($path): ?string
    {
        return str_ireplace(['.jpg', '.jpeg', '.png'], '.webp', $path);
    }

    /**
     * Возвращает путь до WEBP версии на основании пути оригинального файла
     *
     * @param $path
     *
     * @return string|null
     */
    public static function getWebp($path): ?string
    {
        // ALRSUP-1270 Формируем путь до WEBP версии на основании пути оригинального файла
        $webp = self::renameIntoWebp($path);

        // ALRSUP-1270 Проверяем существование WEBP версии
        if (file_exists($webp)) {
            return $webp;
        }

        // ALRSUP-1270 В случае отсутствия WEBP изображения, возвращаем ссылку на оригинал
        return $path;
    }

    /**
     * Уменьшает изображение, на вход принимает id файла
     *
     * @param     $imageId
     * @param int $width
     * @param int $height
     * @param     $options
     *
     * @return string|null
     */
    public static function resizeImageById(
        $imageId,
        int $width,
        int $height,
        $options = BX_RESIZE_IMAGE_PROPORTIONAL
    ): ?string
    {
        // ALRSUP-1270 Уменьшаем изображение при помощи стандартного функционала битрикса
        $picture = CFile::ResizeImageGet($imageId, ['width' => $width, 'height' => $height], $options);

        return $picture ? $picture['src'] : null;
    }

    /**
     * Уменьшает изображение, на вход принимает путь до файла
     *
     * @param     $path
     * @param int $width
     * @param int $height
     *
     * @return string|null
     */
    public static function resizeImageByPath($path, int $width, int $height): ?string
    {
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . $path;
        // Добавил в хеш имени дату изменеиня файла
        $filectime = filectime($path);

        $realitivePath = "/custom_resize_cache/" . md5($absolutePath . '-' . $width . '-' . $height . '-' . $filectime) . '.' . pathinfo($absolutePath, PATHINFO_EXTENSION);
        
        $dest = UPLOAD_PATH . $realitivePath;

        if (!file_exists($dest)) {
            $result = CFile::ResizeImageFile(
                $absolutePath,
                $dest,
                [
                    'width' => $width,
                    'height' => $height,
                ]
            );

            if (!$result) {
                return $path;
            }
        }

        return '/upload' . $realitivePath;
    }
}
