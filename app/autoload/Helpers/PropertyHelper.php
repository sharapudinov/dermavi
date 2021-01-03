<?php

namespace App\Helpers;

use App\Core\BitrixProperty\Property;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\HL\DiamondPacket;
use Arrilot\BitrixModels\Models\ElementModel;
use CIBlockElement;

/**
 * Класс-хелпер для работы со свойствами
 * Class PropertyHelper
 * @package App\Helpers
 */
class PropertyHelper
{
    /**
     * Декодирует из строки в массив множественное свойство
     *
     * @param array $property - Массив, описывающий свойство
     * @return array
     */
    public static function parseMultipleEncodedProperty(array $property): array
    {
        $items = [];
        foreach ($property as $item) {
            parse_str($item, $item);
            $items[str_replace('_', '.', key($item))] = $item[key($item)];
        }

        return $items;
    }

    /**
     * Помогает установить свойство, где PROPERTY_TYPE = 'L'.
     * @param ElementModel $element
     * @param $propName
     * @param $propValue
     * @see https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockproperty/add.php
     * @return null
     */
    public static function setPropertyValuesEx(ElementModel $element, $propName, $propValue)
    {
        $iblockId = iblock_id((get_class($element))::IBLOCK_CODE);

        $valueId = Property::getListPropertyValue($iblockId, $propName, $propValue)->getVariantId();

        CIBlockElement::SetPropertyValuesEx($element['ID'], $iblockId, [$propName => $valueId]);
    }

    /**
     * Возвращает значение и описание указанного свойства элемента
     *
     * @param int $iblockId Идентификатор инфоблока
     * @param int $elementId Идентификатор элемента
     * @param string $code Символьный код свойства
     *
     * @return array|array[]
     */
    public static function getPropertyWithDescription(int $iblockId, int $elementId, string $code): array
    {
        $properties = [];
        $propertiesQuery = CIBlockElement::GetProperty($iblockId, $elementId, [], ['CODE' => $code]);
        while ($property = $propertiesQuery->GetNext()) {
            $properties[] = [
                'VALUE' => $property['VALUE'],
                'DESCRIPTION' => $property['DESCRIPTION']
            ];
        }

        return $properties;
    }

    /**
     * Возвращает флаг необходимости отображения огранки.
     * Если у бриллианта параметр isFancy == true, то огранка не выводится
     *
     * @param DiamondPacket $diamondPacket Модель пакета бриллианта
     * @param string $shape Символьный код формы
     *
     * @return bool
     */
    public static function showCut(DiamondPacket $diamondPacket, string $shape): bool
    {
        if ($diamondPacket->isFancy() || $shape != 'round') {
            return false;
        }

        return true;
    }
}
