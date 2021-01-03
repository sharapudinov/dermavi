<?php

namespace App\Helpers;

use Bitrix\Main\Localization\Loc;

/**
 * Класс-хелпер для работы со строками
 * Class StringHelper
 * @package App\Helpers
 */
class StringHelper
{
    /**
     * Вырезаем все лишние символы (кроме +) из номера
     *
     * @param string $number - Число
     * @return string
     */
    public static function cutAllSymbolsFromNumber(string $number): string
    {
        return str_replace(['(', ')', '-', ' '], '', $number);
    }

    /**
     * Заменяем пробелы в строке на нижнее подчеркивание
     *
     * @param string $string
     * @return string
     */
    public static function changeSpacesToUnderscore(string $string): string
    {
        return str_replace(' ', '_', $string);
    }


    /**
     * Получаем название города в предложном падеже
     *
     * @param string $city
     * @return string
     */
    public static function cityDeclension(string $city): string
    {
        if (!LanguageHelper::isRussianVersion() || preg_match('/[a-zA-Z]/', $city)) {
            return $city;
        }

        $replace = [];
        $replace['2'][] = ['ия','ии'];
        $replace['2'][] = ['ия','ии'];
        $replace['2'][] = ['ий','ом'];
        $replace['2'][] = ['ое','ом'];
        $replace['2'][] = ['ая','ой'];
        $replace['2'][] = ['ль','ле'];
        $replace['1'][] = ['а','е'];
        $replace['1'][] = ['о','е'];
        $replace['1'][] = ['и','ах'];
        $replace['1'][] = ['ы','ах'];
        $replace['1'][] = ['ь','и'];

        $find = false;
        foreach ($replace as $length => $replacement) {
            $cityLength = mb_strlen($city, 'UTF-8');
            $find = mb_substr($city, $cityLength - $length, $cityLength, 'UTF-8');
            foreach ($replacement as $try) {
                if ($find == $try[0]) {
                    $city = mb_substr($city, 0, $cityLength - $length, 'UTF-8');
                    $city .= $try['1'];
                    return $city;
                }
            }
        }

        if ($find == 'е') {
            return $city;
        } elseif ($find == 'й') {
            return substr($city, 0, -1) . 'е';
        } else {
            return $city . 'е';
        }
    }

    /**
     * Получаем ip-адрес пользователя с подчеркиваниями вместо точек
     *
     * @return string
     */
    public static function getUserIpWithUnderscore(): string
    {
        return str_replace('.', '_', $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Получаем строку в нужном языке и падеже
     *
     * @param int $count - Количество
     * @param string[] ...$forms - Ключи из языкового файла
     * @return string
     */
    public static function getMultiLanguagePluralForm(int $count, string ...$forms): string
    {
        return plural_form(
            $count,
            [
                Loc::getMessage($forms[0]),
                Loc::getMessage($forms[1]),
                Loc::getMessage($forms[2])
            ]
        );
    }

    /**
     * Возвращает сокращенную форму характеристики бриллианта
     *
     * @param string $characteristic - Характеристика бриллианта
     *
     * @return string
     */
    public static function getAbbreviatedDiamondCharacteristics(string $characteristic): string
    {
        /** @var string $abbrevCharacteristic - Сокращенная форма характеристики */
        $abbrevCharacteristic = null;

        /** @var array|string[] $characteristicArray - Массив слов, из которых состоит значение характеристики */
        $characteristicArray = explode(' ', $characteristic);

        if (count($characteristicArray) == 1) {
            preg_match(
                '/([bcdfghjklmnpqrstvwxz]{1}|[aeiouy]{1}[bcdfghjklmnpqrstvwxz]{1})/i',
                $characteristic,
                $match
            );
            $abbrevCharacteristic = $match[0];
        } elseif (count($characteristicArray) > 1) {
            foreach ($characteristicArray as $characteristicWord) {
                $abbrevCharacteristic .= substr(trim($characteristicWord), 0, 1);
            }
        }

        return $abbrevCharacteristic;
    }

    /**
     * Транслитерация
     * @param $s
     *
     * @return bool|false|string|string[]|null
     */
    public static function translit($s)
    {
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(["\n", "\r"], " ", $s); // убираем перевод каретки
        $s = preg_replace("/[()]/i", " ", $s); // заменяем скобки на пробел
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'j',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'shch',
            'ы' => 'y',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            'ъ' => '',
            'ь' => ''
        ]);
        $s = preg_replace("/[^0-9a-z-_ ]/i", "-", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус

        return $s; // возвращаем результат
    }

    /**
     * @param        $string
     * @param string $enc
     *
     * @return string
     */
    public function ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc).
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    /**
     * Переводит кодировку из cp1251 в utf-8
     *
     * @param string|null $value
     *
     * @return string|null
     */
    public function encodeCP1251IntoUTF8(?string $value): ?string
    {
        return iconv('CP1251', 'UTF-8', $value);
    }
}
