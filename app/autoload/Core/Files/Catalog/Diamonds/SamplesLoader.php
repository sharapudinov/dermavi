<?php

namespace App\Core\Files\Catalog\Diamonds;

use App\Helpers\FileHelper;
use App\Models\Catalog\Catalog;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику прикрепления сэмплов к бриллиантам (которым не были прикреплены фото ранее)
 * Class SamplesLoader
 *
 * @package App\Core\Files\Catalog\Diamonds
 */
class SamplesLoader extends ImagesLoaderAbstract
{
    /**
     * Записывает в объект необходимые для обновления данные
     *
     * @param array|string[] $images Массив фотографий
     *
     * @return void
     */
    private function loadData(array $images): void
    {
        $images = $this->processImagesArray($images);
        $imagesArray = [];

        foreach ($images as $key => $image) {
            $imagesArray[strtolower(FileHelper::getFileNameWithoutExtension($image['name']))] = $image;
        }

        /** @var Collection|Diamond[] $diamonds */
        $diamonds = Diamond::filter(
            [
                'PROPERTY_PHOTOS' => false,
                '!PROPERTY_SHAPE' => false
            ]
        )->getList();
        foreach ($diamonds as $diamond) {
            $this->updateDiamond($diamond, [$imagesArray[$diamond->shape->getExternalID()]]);
        }
    }

    /**
     * Возвращает ключ, по которому доступен файл в массиве $_FILES
     *
     * @return string
     */
    protected function getFileKey(): string
    {
        return 'samples';
    }

    /**
     * Загружает изображения из директории (если была заархивирована папка, а не сами фотографии).
     *
     * @param string $imagesDirectory Полный путь до директории на сервере
     *
     * @return void
     */
    protected function loadImagesFromDirectory(string $imagesDirectory): void
    {
        $images = FileHelper::getFilesFromDirectory($imagesDirectory);
        $this->loadData($images);
    }

    /**
     * Загружает изображения из директории с архивом (если были заархивированы фотографии, а не папка с ними)
     *
     * @param array|string[] $directoryContent Массив именон файлов в директории с архивом
     *
     * @return void
     */
    protected function loadImagesFromRoot(array $directoryContent): void
    {
        $images = array_filter($directoryContent, function (string $item) {
            return $item != '.' && $item != '..' && $item != 'Thumbs.db';
        });

        $this->loadData($images);
    }
}
