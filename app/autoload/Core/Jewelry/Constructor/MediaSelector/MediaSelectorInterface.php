<?php

namespace App\Core\Jewelry\Constructor\MediaSelector;

/**
 * Interface MediaSelectorInterface
 * @package App\Core\Jewelry\Constructor\MediaSelector
 */
interface MediaSelectorInterface
{
    /**
     * Возвращает набор фотографий или видео 360 в соответствии с номером выбранного набора
     *
     * @return array|string[]
     */
    public function getMedia(): array;
}
