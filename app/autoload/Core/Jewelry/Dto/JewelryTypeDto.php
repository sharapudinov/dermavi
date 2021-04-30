<?php

namespace App\Core\Jewelry\Dto;

use App\Core\LazyLoadDto;
use App\Models\Jewelry\Dicts\JewelryType;
use CFile;

/**
 * Модель представления типа изделия
 * Class JewelryTypeDto
 *
 * @package App\Core\Jewelry\Dto
 *
 * @property int    $id    Идентификатор
 * @property string $code  Символьный код
 * @property string $name  Наименование
 * @property string $image Путь до изображения на сервере
 */
class JewelryTypeDto extends LazyLoadDto
{
    /**
     * JewelryTypeDto constructor.
     *
     * @param JewelryType $type Модель типа изделия
     */
    public function __construct(JewelryType $type)
    {
        $attributes = [
            'id' => $type->getId(),
            'code' => $type->getExternalID(),
            'name' => $type->getName(),
            'image' => CFile::GetPath($type->getImageId())
        ];

        parent::__construct($attributes);
    }
}
