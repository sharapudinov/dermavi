<?php

namespace App\Core\Jewelry\Constructor\BlanksAndDiamonds;

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\CatalogSection;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий получение бриллиантов и заготовок.
 */
class BlanksAndDiamondsRepository
{
    /**
     * Возвращает коллекцию заготовок.
     *
     * @param array|mixed[] $filter Фильтр
     *
     * @return Collection|JewelryBlank[]
     */
    public static function getBlanks(array $filter = []): Collection
    {
        return JewelryBlank::query()->filter($filter)->with('casts')->getList();
    }

    /**
     * Возвращает коллекцию бриллиантов, доступных для заготовок.
     *
     * @return Collection|Diamond[]
     */
    public static function getDiamondsForBlanks(): Collection
    {
        /** @var CatalogSection $section Модель раздела бриллиантов для физ лиц */
        $section = CatalogSection::filter(['CODE' => CatalogSection::FOR_PHYSIC_PERSONS_SECTION_CODE])->first();

        /** @var \App\Core\BitrixProperty\Entity\Property $sellingAvailable Объект, описывающий свойство */
        $sellingAvailable = Property::getListPropertyValue(Diamond::iblockID(), 'SELLING_AVAILABLE', 'Y');

        /** @var Collection|Diamond[] $diamonds Коллекция моделей бриллиантов */
        $diamonds = Diamond::active()
            ->filter([
                'IBLOCK_SECTION_ID' => $section->getId(),
                'PROPERTY_SELLING_AVAILABLE' => $sellingAvailable->getVariantId(),
            ])
            ->with('diamondPacket')
            ->getList();

        foreach ($diamonds as $key => $diamond) {
            if (!$diamond->diamondPacket) {
                $diamonds->forget($key);
            }
        }

        return $diamonds;
    }
}
