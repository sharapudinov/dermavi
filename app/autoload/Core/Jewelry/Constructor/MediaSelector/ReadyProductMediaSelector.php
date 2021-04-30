<?php

namespace App\Core\Jewelry\Constructor\MediaSelector;

use App\Helpers\JewelryCastHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\Dicts\JewelryCast;
use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelryBlankSku;

/**
 * Класс для отбора файлов готового изделия в зависимости от веса или диаметра выбранного бриллианта.
 *
 * Если у изделия в касте есть несколько диапазовов, например, 3-5, 6-8, то:
 *
 * если пользователь выбрал камень с размерами 3-5, то возвращаем изображения с _01_ в имени:
 * zb55k2_01_01_white.jpg
 * zb55k2_01_02_white.jpg
 * zb55k2_01_03_white.jpg
 * zb55k2_01_04_white.jpg
 *
 * если 6-8, то _02_ в имени:
 * zb55k2_02_01_white.jpg
 * zb55k2_02_02_white.jpg
 * zb55k2_02_03_white.jpg
 * zb55k2_02_04_white.jpg
 *
 * Пример файла - "zb55k2_01_02_white.jpg", где:
 * "zb55k2" - слаг оправы;
 * "01" - номер набора фотографий, он же номер диапазона в касте;
 * "02" - просто номер фото в наборе, для каждого диапазона есть несколько фото разного типа;
 * "white" - цвет металла.
 */
class ReadyProductMediaSelector extends AbstractMediaSelector
{
    private JewelryBlank $blank;
    private JewelryCast $cast;
    private Diamond $diamond;

    /**
     * ReadyProductMediaSelector constructor.
     *
     * @param JewelryBlankSku $sku      - модель торгового предложения оправы
     * @param array|null      $allMedia - список всех файлов для данного торгового предложения
     * @param JewelryBlank    $blank    - модель оправы
     * @param JewelryCast     $cast     - модель одного из кастов оправы: первого, или центрального (для трилогии)
     * @param Diamond         $diamond  - модель бриллианта для этого каста
     */
    public function __construct(
        JewelryBlankSku $sku,
        ?array $allMedia,
        JewelryBlank $blank,
        JewelryCast $cast,
        Diamond $diamond
    ) {
        parent::__construct($sku, $allMedia);

        $this->blank = $blank;
        $this->cast = $cast;
        $this->diamond = $diamond;
    }

    /**
     * Определяет номер набора файлов, который нужно вывести,
     * в зависимости от выбранного пользователем размера бриллианта.
     *
     * @return int|null
     */
    protected function getRangeNumber(): ?int
    {
        return JewelryCastHelper::getRangeNumber($this->blank, $this->cast, $this->diamond);
    }
}
