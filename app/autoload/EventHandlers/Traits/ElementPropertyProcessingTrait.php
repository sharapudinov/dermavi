<?php

namespace App\EventHandlers\Traits;

use App\Core\BitrixProperty\Property;

/**
 * Trait ElementPropertyProcessingTrait
 *
 * @package App\EventHandlers\Traits
 */
trait ElementPropertyProcessingTrait
{
    /** @var array $propertiesInfo - Массив, описывающий свойства инфоблока */
    protected static $propertiesInfo;

    /**
     * Id инфоблока
     *
     * @return int
     */
    abstract protected static function getIBlockId(): int;

    /**
     * Задействованные свойства
     *
     * @return array
     */
    abstract protected static function getInvolvedProps(): array;

    /**
     * @param int $iblockId
     * @param array|string[] $codes - Массив символьных кодов, информацию о которых надо запросить
     * @return array
     */
    protected static function getPropertiesInfo(int $iblockId, array $codes = []): array
    {
        $property = new Property($iblockId);
        foreach ($codes as $code) {
            $property->addPropertyToQuery($code);
        }

        return $property->getPropertiesInfo();
    }

    /**
     * @param string $propCode
     * @return array
     */
    protected static function getPropertyMeta(string $propCode): array
    {
        if (self::$propertiesInfo === null) {
            self::$propertiesInfo = static::getPropertiesInfo(static::getIBlockId(), static::getInvolvedProps());
        }

        return self::$propertiesInfo[$propCode] ?? [];
    }

    /**
     * @param string $propCode
     * @param string $value
     * @return int
     */
    protected static function getPropertyEnumValueId(string $propCode, string $value): int
    {
        $propMeta = static::getPropertyMeta($propCode);
        if (isset($propMeta['VALUES'][$value])) {
            return (int)$propMeta['VALUES'][$value];
        }

        return 0;
    }

    /**
     * @param string $propCode
     * @param $propertyValues
     * @return bool
     */
    protected static function isPropertyPassed(string $propCode, $propertyValues): bool
    {
        $propMeta = static::getPropertyMeta($propCode);

        return $propMeta && array_key_exists($propMeta['PROPERTY_ID'], $propertyValues);
    }

    /**
     * @param string $propCode
     * @param $propertyValues
     * @return mixed|null
     */
    protected static function extractProperty(string $propCode, $propertyValues)
    {
        $propMeta = static::getPropertyMeta($propCode);

        return $propMeta ? $propertyValues[$propMeta['PROPERTY_ID']] : null;
    }

    /**
     * @param string $propCode
     * @param $propertyValues
     * @param bool $multiple возвращать массив значений (иначе вернется только первое значение), если св-во задано
     * @return mixed|null
     */
    protected static function extractPropertyValue(string $propCode, $propertyValues, bool $multiple = false)
    {
        $propVal = static::extractProperty($propCode, $propertyValues);
        if ($propVal !== null) {
            if (!is_array($propVal)) {
                return $multiple ? [$propVal] : $propVal;
            }

            $result = [];
            foreach ($propVal as $val) {
                if (isset($val['VALUE'])) {
                    $result[] = $val['VALUE'];
                }
            }
            if (!$result) {
                return null;
            }

            return $multiple ? $result : reset($result);
        }

        return null;
    }
}
