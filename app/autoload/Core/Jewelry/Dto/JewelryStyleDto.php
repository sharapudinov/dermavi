<?php

namespace App\Core\Jewelry\Dto;

use App\Core\LazyLoadDto;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use App\Models\Jewelry\Dicts\JewelryStyle;
use CFile;

/**
 * Модель представления стиля ЮБИ
 * Class JewelryStyleDto
 *
 * @package App\Core\Jewelry\Dto
 *
 * @property int        $id         Идентификатор
 * @property string     $xmlId      Внешний идентификатор
 * @property string     $name       Название
 * @property int        $imageId    Идентификатор изображения
 * @property string     $image      Путь до изображения
 */
class JewelryStyleDto extends LazyLoadDto
{
    /**
     * JewelryStyleDto constructor.
     *
     * @param JewelryAssistantStyle $style Модель стиля изделия
     * @param int $imageId Идентификатор изображения
     */
    public function __construct(JewelryAssistantStyle $style, int $imageId)
    {
        $attributes = [
            'id' => $style->getId(),
            'xmlId' => $style->getExternalID(),
            'name' => $style->getName(),
            'imageId' => $imageId,
            'image' => CFile::GetPath($imageId)
        ];

        parent::__construct($attributes);
    }
}
