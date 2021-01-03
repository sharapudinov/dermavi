<?php

namespace App\Core\Files\Catalog\Diamonds;

use App\Helpers\FileHelper;
use App\Models\Catalog\Diamond;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику прикрепления изображений определенным бриллиантам
 * Class SpecificPhotosLoader
 *
 * @package App\Core\Files\Catalog\Diamonds
 */
class SpecificPhotosLoader extends ImagesLoaderAbstract
{
    /**
     * Возвращает ключ, по которому доступен файл в массиве $_FILES
     *
     * @return string
     */
    protected function getFileKey(): string
    {
        return 'images';
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
        $content = FileHelper::getFilesFromDirectory($imagesDirectory);
        $this->loadImagesFromRoot($content);
    }

    /**
     * Загружает изображения из директории с архивом (если были заархивированы фотографии, а не папка с ними)
     *
     * @param array|string[] $directoryContent Массив имен файлов в директории с архивом
     *
     * @return void
     */
    protected function loadImagesFromRoot(array $directoryContent): void
    {
        $diamondsPackets = [];
        foreach ($directoryContent as $item) {
            $diamondsPackets[] = FileHelper::getFileNameWithoutExtension($item);
        }

        /** @var Collection|Diamond[] $diamonds */
        $diamonds = Diamond::filter(['CODE' => $diamondsPackets])->getList();
        foreach ($directoryContent as $item) {
            $isDir = false;
            if (is_dir($this->imagesDirectory . '/' . $item)) {
                $images = FileHelper::getFilesFromDirectory($this->imagesDirectory . '/' . $item);
                $isDir = true;
            } else {
                $images = [$item];
            }

            $code = FileHelper::getFileNameWithoutExtension($item);

            /** @var Diamond $diamond */
            $diamond = $diamonds->first(function (Diamond $diamond) use ($code) {
                return $diamond->getPacketNumber() == $code;
            });

            if ($diamond) {
                $images = $this->processImagesArray($images, $isDir ? $item : null);
                $this->updateDiamond($diamond, $images);
            }
        }
    }
}
