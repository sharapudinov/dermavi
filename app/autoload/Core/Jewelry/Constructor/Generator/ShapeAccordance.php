<?php

namespace App\Core\Jewelry\Constructor\Generator;

use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryBlank;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику определения соответствия бриллианта и заготовки по форме
 * Class ShapeAccordance
 *
 * @package App\Core\Jewelry\Constructor\Generator
 */
class ShapeAccordance implements AccordanceInterface
{
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
        // Проверка на соответствия групп форм (если таковы привязаны к бриллиантам и изделию)
        // или по соответствию id форм
        return $diamonds->filter(function (Diamond $diamond) use ($blank) {
            if ($diamond->diamondPacket->getFormsAccordancesGroup() && $blank->formsAccordanceGroup) {
                return $diamond->diamondPacket->getFormsAccordancesGroup()->getId() == $blank->formsAccordanceGroup->getId()
                    || $diamond->diamondPacket->getFormID() == $blank->getShapeXmlId();
            } else {
                return $diamond->diamondPacket->getFormID() == $blank->getShapeXmlId();
            }
        });
    }
}
