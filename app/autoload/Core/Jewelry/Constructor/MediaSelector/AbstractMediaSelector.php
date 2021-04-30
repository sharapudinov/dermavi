<?php

namespace App\Core\Jewelry\Constructor\MediaSelector;

use App\Models\Jewelry\JewelryBlankSku;

/**
 * Класс для отбора фотографий и видео 360 торгового предложения (ИБ "Торговые предложения для заготовок ЮБИ").
 * Подбирает файлы в соответствии с выбранным в методе getRangeNumber номером набора.
 *
 * @package App\Core\Jewelry\Constructor\MediaSelector
 */
abstract class AbstractMediaSelector implements MediaSelectorInterface
{
    protected JewelryBlankSku $sku;
    protected ?array $allMedia;

    /**
     * AbstractMediaSelector constructor.
     *
     * @param JewelryBlankSku $sku - Модель торгового предложения оправы
     * @param array|null $allMedia - Список всех файлов для данного торгового предложения
     */
    public function __construct(JewelryBlankSku $sku, ?array $allMedia)
    {
        $this->sku = $sku;
        $this->allMedia = $allMedia;
    }

    /**
     * Возвращает набор файлов в соответствии с номером выбранного набора
     *
     * @return array|string[]
     */
    public function getMedia(): array
    {
        if (empty($this->allMedia)) {
            return [];
        }

        $groupedMedia = $this->groupMediaByRangeNumbers($this->allMedia);

        if (empty($groupedMedia)) {
            return [];
        }

        $rangeNumber = $this->getRangeNumber();

        if (!$rangeNumber) {
            return [];
        }

        return $groupedMedia[$rangeNumber] ?? [];
    }

    /**
     * Группирует доступные файлы по сквозным номерам диапазонов в кастах
     *
     * @param array|string[] $items
     *
     * @return array
     */
    protected function groupMediaByRangeNumbers(array $items): array
    {
        $groupedMedia = [];

        foreach ($items as $item) {
            if (!is_string($item) || !$item) {
                continue;
            }

            $mediaName = basename($item);

            if (!is_string($mediaName) || !$mediaName) {
                continue;
            }

            preg_match('/^[^_]+_([\d]+)_/', $mediaName, $match);

            if (empty($match[1])) {
                continue;
            }

            $rangeNumber = (int)$match[1];

            if ($rangeNumber < 1) {
                continue;
            }

            if (!isset($groupedMedia[$rangeNumber])) {
                $groupedMedia[$rangeNumber] = [];
            }

            $groupedMedia[$rangeNumber][] = $item;
        }

        return $groupedMedia;
    }

    /**
     * Определяет номер набора файлов для вывода
     *
     * @return int|null
     */
    abstract protected function getRangeNumber(): ?int;
}
