<?php

namespace App\Console;

use App\Helpers\ImageHelper;

/**
 * Class ConvertImageToWebpCommand
 *
 * @package App\Console
 */
class ConvertImageToWebpCommand extends BaseCommand
{
    const BASE_PATH = UPLOAD_PATH . DIRECTORY_SEPARATOR;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('image:convert_to_webp')
             ->setDescription('Convert JPG, JPEG, PNG to WEBP');
    }

    /**
     * Возвращает массив расширений, доступных для конвертации
     *
     * @return string[]
     */
    private function getAllowedImageExtensions(): array
    {
        return [
            'jpg',
            'jpeg',
            'png',
        ];
    }

    /**
     * Возвращает массив директорий, в которых необходимо конвертировать изображения
     * Дирректории указываются относительно каталога upload/
     *
     * @return string[]
     */
    private function getAllowedDirs(): array
    {
        return [
            'resize_cache',
            'custom_resize_cache',
        ];
    }

    /**
     * Конвертирует JPG, JPEG, PNG в WEBP при помощи хелпера ImageHelper
     *
     * @param $path - Путь до оригинальной картинки
     *
     * @return void
     */
    private function convertToWebp($path): void
    {
        // ALRSUP-1270 Обрезаем часть пути до upload/, т.к. хелперу нужны пути от корня сайта
        $filePath = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', $path->getPathname());
        // ALRSUP-1270 Конвертируем изображения в WEPB при помощи хелпера
        ImageHelper::createWebp($filePath);
    }

    /**
     * Проверяет существование WEBP версии
     *
     * @param $path - Путь до оригинальной картинки
     *
     * @return bool
     */
    private function isExistsWebp($path): bool
    {
        $webp = ImageHelper::renameIntoWebp($path);

        return file_exists($webp);
    }

    protected function fire()
    {
        $this->info('Converting JPG, JPEG, PNG to WEBP...');
        $this->logInfo('Converting JPG, JPEG, PNG to WEBP...');

        $dirs = $this->getAllowedDirs();

        if (!empty($dirs)) {
            foreach ($dirs as $path) {
                $this->info('Scanning directory: ' . $path);
                $this->logInfo('Scanning directory: ' . $path);

                // ALRSUP-1270 Итерируем каталог, игнорируем точечные файлы "." и ".."
                $dir = new \RecursiveDirectoryIterator(self::BASE_PATH . $path, \FilesystemIterator::SKIP_DOTS);

                // ALRSUP-1270 Фильтруем содержимое каталогов
                $filter = new \RecursiveCallbackFilterIterator(
                    $dir,
                    function ($item, $key, $iterator) {
                        // Разрешаем рекурсию по вложенным директориям
                        if ($iterator->hasChildren()) {
                            return true;
                        }

                        // Проверяем, что файл соотвествует доступному для конвертации расширению
                        if ($item->isFile() && in_array(
                                $item->getExtension(),
                                $this->getAllowedImageExtensions()
                            ) && !$this->isExistsWebp($item->getPathname())) {
                            return true;
                        }

                        return false;
                    }
                );

                // ALRSUP-1270 Получаем список файлов
                $files = new \RecursiveIteratorIterator($filter);
                $totalFiles = iterator_count($files);

                $this->info($path . ': ' . $totalFiles . ' images will be converted to WEBP');
                $this->logInfo($path . ': ' . $totalFiles . ' images will be converted to WEBP');

                // ALRSUP-1270 Перебираем файлы
                if ($totalFiles > 0) {
                    foreach ($files as $file) {
                        $this->convertToWebp($file);
                    }
                }
            }

            $this->info('Converting is done');
            $this->logInfo('Converting is done');
        } else {
            $this->info('Directory list not specified');
            $this->logInfo('Directory list not specified');
        }
    }
}