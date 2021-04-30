<?php

namespace App\Core\Jewelry\Constructor\MediaSelector;

use App\Models\Jewelry\JewelryBlankSku;

/**
 * Класс для отбора фотографий и видео 360 по умолчанию
 * для торгового предложения (ИБ "Торговые предложения для заготовок ЮБИ").
 *
 * @package App\Core\Jewelry\Constructor\MediaSelector
 */
class DefaultMediaSelector extends AbstractMediaSelector
{
    private ?int $defaultSetNumber;

    /**
     * DefaultMediaSelector constructor.
     *
     * @param JewelryBlankSku $sku - модель торгового предложения оправы
     * @param array|null $allMedia - список всех файлов для данного торгового предложения
     * @param int|null $defaultSetNumber - номер набора файлов по умолчанию
     */
    public function __construct(JewelryBlankSku $sku, ?array $allMedia, ?int $defaultSetNumber)
    {
        parent::__construct($sku, $allMedia);

        $this->defaultSetNumber = $defaultSetNumber;
    }

    /**
     * Возвращает из оправы номер набора файлов по умолчанию
     *
     * @return int|null
     */
    protected function getRangeNumber(): ?int
    {
        return $this->defaultSetNumber;
    }
}
