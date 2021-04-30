<?php

namespace App\Core\Files\Catalog\Diamonds;

use App\Helpers\FileHelper;
use App\Models\Catalog\Catalog;
use CFile;
use Throwable;
use ZipArchive;

/**
 * Абстрактный класс, описывающий логику прикрепления фотографий бриллиантам
 * Class ImagesLoaderAbstract
 *
 * @package App\Core\Files\Catalog\Diamonds
 */
abstract class ImagesLoaderAbstract
{
    /** @var string $imagesDirectory Директория с изображениями */
    protected $imagesDirectory;

    /**
     * Прикрепляет бриллиантам фотографии
     *
     * @return void
     */
    public function importImages(): void
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/../temporary_files/';
        $file = $path . $_FILES[$this->getFileKey()]['name'];

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        move_uploaded_file($_FILES[$this->getFileKey()]['tmp_name'], $file);

        if (preg_match("(.*.zip)", $file)) {
            $zip = new ZipArchive();

            if ($zip->open($file) !== true) {
                return;
            }

            $zip->extractTo($path);
        }

        $directoryContent = FileHelper::getFilesFromDirectory($path);
        $imagesDirectory = null;
        foreach ($directoryContent as $item) {
            $directory = preg_replace("(\.[a-z]{2,4})", "", $_FILES[$this->getFileKey()]['name']);
            if (strstr($directory, $item)) {
                $imagesDirectory = $path . $item;
                break;
            }
        }

        if ($imagesDirectory) {
            $this->imagesDirectory = $imagesDirectory;
            $this->loadImagesFromDirectory($this->imagesDirectory);
        } else {
            $this->imagesDirectory = $path;
            $this->loadImagesFromRoot($directoryContent);
        }

        unlink($file);
        $content = scandir($this->imagesDirectory);
        foreach ($content as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (is_dir($this->imagesDirectory . '/' . $item)) {
                $subDirContent = scandir($this->imagesDirectory . '/' . $item);
                foreach ($subDirContent as $subItem) {
                    if ($item == '.' || $item == '..') {
                        continue;
                    }

                    unlink($this->imagesDirectory . '/' . $item . '/' . $subItem);
                }

                rmdir($this->imagesDirectory . '/' . $item);
            } else {
                unlink($this->imagesDirectory . '/' . $item);
            }

        }
        rmdir($this->imagesDirectory);
    }

    /**
     * Сохраняет изображения в бд и возвращает массив для сохранения фото в ИБ бриллиантов
     *
     * @param array|string[] $images Массив изображений
     * @param string|null $subDir Поддиректория с изображениями
     *
     * @return array|array[]
     */
    protected function processImagesArray(array $images, string $subDir = null): array
    {
        $processedImages = [];
        foreach ($images as $image) {
            $makeFileArray = CFile::MakeFileArray(
                $this->imagesDirectory . '/' . ($subDir ? $subDir . '/' : '') . $image
            );
            $processedImages[] = CFile::MakeFileArray(CFile::SaveFile($makeFileArray, 'iblock'));
        }

        return $processedImages;
    }

    /**
     * Обновляет бриллиант
     *
     * @param Diamond $diamond Модель бриллианта
     * @param array|array[] $images Массив фотографий
     *
     * @return void
     */
    protected function updateDiamond(Diamond $diamond, array $images): void
    {
        try {
            $diamond->update(['PROPERTY_PHOTOS_VALUE' => $images]);
        } catch (Throwable $exception) {
            logger('import')
                ->error(
                    get_called_class() . ': Не удалось импортировать фото для бриллианта #' . $diamond->getId()
                    . '. Причина: ' . $exception->getMessage()
                );
        }
    }

    /**
     * Возвращает ключ, по которому доступен файл в массиве $_FILES
     *
     * @return string
     */
    abstract protected function getFileKey(): string;

    /**
     * Загружает изображения из директории (если была заархивирована папка, а не сами фотографии).
     *
     * @param string $imagesDirectory Полный путь до директории на сервере
     *
     * @return void
     */
    abstract protected function loadImagesFromDirectory(string $imagesDirectory): void;

    /**
     * Загружает изображения из директории с архивом (если были заархивированы фотографии, а не папка с ними)
     *
     * @param array|string[] $directoryContent Массив имен файлов в директории с архивом
     *
     * @return void
     */
    abstract protected function loadImagesFromRoot(array $directoryContent): void;
}
