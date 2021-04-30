<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Helpers\CadasDictHelper;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\HL\CatalogFluorescence;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по флуоресценции
 * Class FluorescenceAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class FluorescenceAccordance implements AccordanceInterface, CombinationAccordanceInterface
{
    /** @var Collection|CatalogFluorescence[] $fluorescences Коллекция моделей флуоресценций */
    private $fluorescences;

    /**
     * FluorescenceAccordance constructor.
     */
    public function __construct()
    {
        $this->fluorescences = CadasDictHelper::getCatalogFluorescences()->sortBy(
            function (CatalogFluorescence $fluorescence) {
                return (int) $fluorescence->getSort();
            }
        );
    }

    /**
     * Возвращает коллекцию моделей бриллиантов, соответствующих заготовке
     *
     * @param JewelryBlank $blank Модель заготовки
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов
     *
     * @return Collection|Diamond[]
     */
    public function getAccordingDiamonds(JewelryBlank $blank, Collection $diamonds): Collection
    {
        // Проверка на то, что количество бриллиантов определенной флуоресценции не меньше количества слотов для них
        $groupedDiamonds = $diamonds->groupBy('PROPERTY_FLUOR_VALUE');
        return AccordanceHelper::getDiamondsForCharacteristic($blank, $groupedDiamonds);
    }

    /**
     * Возвращает истину, если у всех камней в комбинации одинаковое значение флуоресценции
     *
     * @param Collection|Diamond[] $diamonds Коллекция моделей бриллиантов текущей комбинации
     *
     * @return bool
     */
    public function isCombinationAccords(Collection $diamonds): bool
    {
        return $diamonds->pluck('PROPERTY_FLUOR_VALUE')->unique()->count() === 1;
    }
}
