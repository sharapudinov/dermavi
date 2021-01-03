<?php

namespace App\Core\BitrixProperty;

use App\Core\BitrixProperty\Entity\Property as PropertyEntity;
use App\Helpers\TTL;
use CIBlockProperty;
use CUserTypeEntity;
use CUserFieldEnum;
use Illuminate\Support\Collection;

/**
 * Класс для работы с битриксовыми свойствами
 * Class Property
 * @package App\Core\BitrixProperty
 */
class Property
{
    /** @var int $iblockId - Идентификатор инфоблока, у которого будут просматриваться свойства */
    private $iblockId;

    /** @var array|mixed[] $properties - Массив, описывающий свойства и их значения, которые надо запросить */
    private $properties;

    /**
     * Property constructor.
     *
     * @param int $iblockId - Идентификатор инфоблока, у которого будут просматриваться свойства
     */
    public function __construct(int $iblockId)
    {
        $this->iblockId = $iblockId;
    }

    /**
     * Получаем информацию о варианте значения из свойства типа "Список" для пользовательских свойств
     *
     * @param string $fieldName - Название поля
     * @param string $variantCode - Символьный код нужного варианта
     * @return PropertyEntity
     */
    public static function getUserTypeListPropertyValue(string $fieldName, string $variantCode): PropertyEntity
    {
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_getUserTypeListPropertyValue'
            . '_' . $fieldName
            . '_' . $variantCode;

        return cache($cacheKey, TTL::DAY, function () use ($fieldName, $variantCode) {
            $property = CUserTypeEntity::GetList([], ['FIELD_NAME' => $fieldName])->Fetch();
            $userFieldEnum = new CUserFieldEnum();
            return new PropertyEntity(
                $property,
                $userFieldEnum->GetList([], ['USER_FIELD_ID' => $property['ID'], 'XML_ID' => $variantCode])->Fetch()
            );
        });
    }

    /**
     * Получает информацию о варианте значения из свойства типа "Список" инфоблока
     *
     * @param int $iblockId - Идентификатор инфоблока
     * @param string $fieldName - Символьный код
     * @param string $value - Значение, информацию о котором надо найти
     * @return PropertyEntity
     */
    public static function getListPropertyValue(int $iblockId, string $fieldName, string $value): PropertyEntity
    {
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_getListPropertyValue'
            . '_' . $iblockId
            . '_' . $fieldName
            . '_' . $value;

        return cache($cacheKey, TTL::DAY, function () use ($iblockId, $fieldName, $value) {
            $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName])->Fetch();
            return new PropertyEntity(
                $property,
                CIBlockProperty::GetPropertyEnum($property['ID'], [], ['VALUE' => $value])->Fetch()
            );
        });
    }
    
    /**
     * @param int $iblockId - идентификатор инфоблока
     * @param string $fieldName - символьный код свойства
     * @return Collection|PropertyEntity[]
     */
    public static function getListPropertyValues(int $iblockId, string $fieldName): Collection
    {
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_getListPropertyValues'
            . '_' . $iblockId
            . '_' . $fieldName;
    
        return cache($cacheKey, TTL::DAY, function () use ($iblockId, $fieldName) {
            $propertyValues = collect();
            
            $property = CIBlockProperty::GetList([], ['IBLOCK_ID' => $iblockId, 'CODE' => $fieldName])->Fetch();
            $enumResult = CIBlockProperty::GetPropertyEnum($property['ID'], [], []);
            while ($enum = $enumResult->Fetch()) {
                $propertyValues->put($enum['XML_ID'], new PropertyEntity(
                    $property,
                    $enum
                ));
            }
            
            return $propertyValues;
        });
    }

    /**
     * Возвращает массив, описывающий указанное пользовательское свойство
     *
     * @param string $tableCode Символьный код таблицы
     * @param array|string[] $fieldNames Символьные коды полей
     *
     * @return array|mixed[]
     */
    public static function getUserFields(string $tableCode, array $fieldNames): array
    {
        $whereCondition = 'field.FIELD_NAME = "' . $fieldNames[0] . '"';
        if (count($fieldNames) > 1) {
            foreach ($fieldNames as $fieldName) {
                $whereCondition .= ' OR field.FIELD_NAME = "' . $fieldName . '"';
            }
        }

        $fieldQuery = db()->query(
            'SELECT entity.ID, entity.TABLE_NAME, field.ENTITY_ID, field.FIELD_NAME, field.ID
            FROM b_hlblock_entity as entity
            LEFT JOIN b_user_field as field ON field.ENTITY_ID LIKE CONCAT(\'%\', entity.ID, \'%\')
            WHERE entity.TABLE_NAME = "' . $tableCode . '" AND (' . $whereCondition . ');'
        );

        $fieldsInfo = [];
        while ($field = $fieldQuery->fetch()) {
            $fieldsInfo[] = $field;
        }

        return $fieldsInfo;
    }

    /**
     * Добавляет новые свойства в массив для запроса
     *
     * @param string $code
     * @param array|null $values
     * @return Property
     */
    public function addPropertyToQuery(string $code, array $values = null): self
    {
        if (!array_key_exists($code, $this->properties)) {
            $this->properties[$code] = $values;
        }

        return $this;
    }

    /**
     * Возвращает массив с информацией о свойствах
     *
     * @return array|mixed[]
     */
    public function getPropertiesInfo(): array
    {
        /** @var array|string[] $propertiesCodes - Массив символьных кодов свойств */
        $propertiesCodes = array_keys($this->properties);

        /** @var string $cachekey - Ключ для кеша */
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_getPropertiesInfo_'
            . $this->iblockId . '_'
            . implode('_', $propertiesCodes);

        return cache($cacheKey, TTL::DAY, function () use ($propertiesCodes) {
            /** @var array|mixed[] $propertiesInfo - Массив с информацией о свойствах */
            $propertiesInfo = [];

            /** @var string $codeExpression - Строка, описывающая запрос для символьных кодов свойств */
            $codeExpression = '';

            $increment = 0;
            foreach ($propertiesCodes as $propertyCode) {
                if ($increment++ > 0) {
                    $codeExpression .= 'OR ';
                }

                $codeExpression .= 'CODE = "' . $propertyCode . '" ';
            }

            /** @var \Bitrix\Main\DB\MysqliResult $propertiesQuery - Объект, описывающий результат запроса */
            $propertiesQuery = db()->query(
                'SELECT property.ID as PROPERTY_ID, property.CODE, enum.ID as ENUM_ID, enum.VALUE'
                . ' FROM b_iblock_property as property'
                . ' LEFT JOIN b_iblock_property_enum as enum ON property.ID = enum.PROPERTY_ID'
                . ' WHERE IBLOCK_ID = ' . $this->iblockId
                . ' AND (' . $codeExpression . ')'
            );
            while ($property = $propertiesQuery->fetch()) {
                $propertiesInfo[$property['CODE']]['PROPERTY_ID'] = $property['PROPERTY_ID'];
                $propertiesInfo[$property['CODE']]['CODE'] = $property['CODE'];
                if ($property['VALUE']) {
                    $propertiesInfo[$property['CODE']]['VALUES'][$property['VALUE']] = $property['ENUM_ID'];
                }
            }

            return $propertiesInfo;
        });
    }

    /**
     * Возвращает массив, описывающий свойства сущности USER
     *
     * @param array|string[] $propertiesXmlId Массив XML_ID свойств
     *
     * @return array|array[]
     */
    public static function getPropertiesInfoFromUser(array $propertiesXmlId): array
    {
        /** @var string $cachekey - Ключ для кеша */
        $cacheKey = get_class_name_without_namespace(self::class)
            . '_getPropertiesInfoFromUser_'
            . implode('_', $propertiesXmlId);

        return cache($cacheKey, TTL::DAY, function () use ($propertiesXmlId) {
            $whereCondition = '';
            $increment = 0;
            foreach ($propertiesXmlId as $xmlId) {
                if ($increment++ > 0) {
                    $whereCondition .= 'OR ';
                }

                $whereCondition .= 'XML_ID = "' . $xmlId . '" ';
            }

            $propertiesQuery = db()->query('SELECT ID, XML_ID FROM b_user_field WHERE ' . $whereCondition);

            $propertiesInfo = [];
            while ($property = $propertiesQuery->fetch()) {
                $propertiesInfo[$property['XML_ID']]['PROPERTY_ID'] = $property['ID'];
                $propertiesInfo[$property['XML_ID']]['CODE'] = $property['XML_ID'];
            }

            return $propertiesInfo;
        });
    }
}
