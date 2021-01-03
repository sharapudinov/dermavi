<?php

namespace App\Core\Auxiliary\PropertyType;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Application;
use Exception;


/**
 * Класс-обработчик добавления нового свойства инфоблока "ссылка"
 * Class IblockPropertyLink
 * @package App\Core\Auxiliary\PropertyType
 */
class IblockPropertyLink
{
    /**
     * Получить описание свойства для Битрикс
     * @return array
     */
    public function getUserTypeDescription(): array
    {
        return [
            'PROPERTY_TYPE' => PropertyTable::TYPE_STRING,
            'USER_TYPE' => 'Link',
            'DESCRIPTION' => 'Ссылка',
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'GetPublicFilterHTML' => [__CLASS__, 'GetFilterHTML'],
            'GetAdminFilterHTML' => [__CLASS__, 'GetFilterHTML'],
            'ConvertToDB' => [__CLASS__, 'ConvertToFromDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertToFromDB']
        ];
    }
    
    /**
     * Получить HTML-код для редактирования свойства
     * @param array $property - свойство
     * @param array $value - значение
     * @param array $htmlControlName
     * @return string
     */
    public function getPropertyFieldHtml(array $property, array $value, array $htmlControlName): string
    {
        if (!array_key_exists('VALUE', $value) && $property['MULTIPLE'] == 'Y') {
            $value = array_shift($value);
        }
        
        return '<a href="' . $value['VALUE'] . '">'
            . ($value['DESCRIPTION'] != '' ? $value['DESCRIPTION'] : $value['VALUE'])
            . '</a>';
    }
    
    /**
     * Установить фильтр элементов по значению свойства
     * @param array $property - свойство
     * @param array $htmlControlName - HTMl-код для фильтрации по свойству
     * @param array $filter - параметры фильтрации
     * @param bool $filtered - фильтр применен?
     * @throws Exception
     */
    public function addFilterFields(array $property, array $htmlControlName, array &$filter, bool &$filtered): void
    {
        $request = Application::getInstance()->getContext()->getRequest()->toArray();
        
        if (isset($request[$htmlControlName['VALUE']])) {
            $prefix = $request[$htmlControlName['VALUE']] == 'Y' ? '=' : '!=';
            $filter[$prefix . 'PROPERTY_' . $property['ID']] = 'Y';
            $filtered = true;
        }
    }
    
    /**
     * Получить HTML-код для фильтрации по свойству
     * @param array $property - свойство
     * @param array $htmlControlName - HTMl-код для фильтрации по свойству
     * @return string
     * @throws Exception
     */
    public function getFilterHTML(
        /** @noinspection PhpUnusedParameterInspection */
        array $property,
        array $htmlControlName
    ): string {
        
        return '<a href="' . $htmlControlName['VALUE'] . '">' . $htmlControlName['VALUE'] . '</a>';
    }
    
    /**
     * Конвертировать значение свойства из/в БД
     * @param array $property - свойство
     * @param array $value - значение
     * @return array
     */
    public function convertToFromDB(
        /** @noinspection PhpUnusedParameterInspection */
        array $property,
        array $value
    ): array {
        
        return $value;
    }
    
    /**
     * Получить длину значений свойства
     * @param array $property - свойство
     * @param array $value - значение
     * @return int
     */
    public function getLength(
        /** @noinspection PhpUnusedParameterInspection */
        array $property,
        array $value
    ): int {
        return 1;
    }
    
}
