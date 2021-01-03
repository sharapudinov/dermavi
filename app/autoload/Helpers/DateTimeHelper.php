<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeZone;
use Exception;
use Throwable;

/**
 * Класс-хелпер для работы с датой/временем
 * Class DateTimeHelper
 * @package App\Helpers
 */
class DateTimeHelper
{
    /**
     * Получить дату и время в зависимости от часового пояса
     *
     * @param string $zone
     * @return DateTime
     * @throws Exception
     */
    public static function getDateTimeByGeoTimeZone(string $zone): DateTime
    {
        return new DateTime('now', new DateTimeZone($zone));
    }

    /** @noinspection PhpDocMissingThrowsInspection */
    /**
     * @return DateTime
     */
    public static function getDateTimeMoscow(): DateTime
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return static::getDateTimeByGeoTimeZone('Europe/Moscow');
    }

    /**
     * Изменяем формат времени
     *
     * @param DateTime $time - Время
     * @return string
     */
    public static function transformTimeBySiteVersion(DateTime $time): string
    {
        if (LanguageHelper::getLanguageVersion() == 'ru') {
            $transformedTime = $time->format('H:s');
        } else {
            $transformedTime = $time->format('g:i A');
        }

        return $transformedTime;
    }

    /**
     * Форматирует дату и время
     *
     * @param string $dateTime - Дата и время
     * @param string $format - Формат
     * @return string
     * @throws Exception
     */
    public static function formatDateTime(string $dateTime, string $format): string
    {
        return (new DateTime($dateTime))->format($format);
    }

    /**
     * Трансформирует дату в человекопонятный вид на нужном языке
     *
     * @param string $dateTime - Дата и время
     * @param string|null $language Язык, на котором нужно вернуть дату
     * @param bool $showTime Флаг необходимости показа времени
     *
     * @return string
     *
     * @throws Exception
     */
    public static function formatDateTimeToHuman(string $dateTime, string $language = null, bool $showTime = false): string
    {
        $formattedDate = (new DateTime($dateTime));
        return $formattedDate->format('d')
            . ' ' . DateTimeHelper::getMonthInNecessaryLanguage(
                $formattedDate, true, 'genitive', $language
            ) . ' ' . $formattedDate->format('Y')
            . ($showTime ? ', ' . $formattedDate->format('H:i') : '');
    }

    /**
     * Получает диапазон дат
     *
     * @param string $dateOne - Начальная дата
     * @param string $dateTwo - Конечная дата
     * @param string|null $language - Язык
     *
     * @return string
     * @throws Exception
     */
    public static function formDatesRange(string $dateOne, string $dateTwo, string $language = null): string
    {
        /** @var DateTime $dateOne - Начальная дата */
        $dateOne = new DateTime($dateOne);
        $dateOneDay = $dateOne->format('j');
        $dateOneYear = $dateOne->format('Y');
        if ($language == LanguageHelper::ENGLISH_LANGUAGE) {
            $dateOneMonth = $dateOne->format('F');
        } else {
            $dateOneMonth = static::getMonthInNecessaryLanguage(
                $dateOne,
                true,
                'genitive',
                $language
            );
        }

        /** @var DateTime $dateTwo - Конечная дата */
        $dateTwo = new DateTime($dateTwo);
        $dateTwoDay = $dateTwo->format('j');
        $dateTwoYear = $dateTwo->format('Y');
        if ($language == LanguageHelper::ENGLISH_LANGUAGE) {
            $dateTwoMonth = $dateTwo->format('F');
        } else {
            $dateTwoMonth = static::getMonthInNecessaryLanguage(
                $dateTwo,
                true,
                'genitive',
                $language
            );
        }

        $dateRange = $dateOneDay;
        if ($dateOneMonth == $dateTwoMonth && $dateOneYear == $dateTwoYear) {
            $dateRange .= '-' . $dateTwoDay . ' ' . $dateOneMonth . ' ' . $dateOneYear;
        } else {
            $dateRange .= ' ' . $dateOneMonth;
            if ($dateOneYear == $dateTwoYear) {
                $dateRange .= ' - ' . $dateTwoDay . ' ' . $dateTwoMonth . ' ' . $dateOneYear;
            } else {
                $dateRange .= ' ' . $dateOneYear . ' -<br />' . $dateTwoDay . ' ' . $dateTwoMonth . ' ' . $dateTwoYear;
            }
        }

        return $dateRange;
    }

    /**
     * Возвращает месяц на нужном языке и в нужном склонении
     *
     * @param DateTime $date - Объект даты
     * @param bool $full - Полное название дат
     * @param string $case - Падеж дат
     * @param string|null $language - Язык
     * @param string $format - Формат
     *
     * @return string
     */
    public static function getMonthInNecessaryLanguage(
        DateTime $date,
        bool $full = false,
        string $case = '',
        string $language = null,
        string $format = 'd.m.Y'
    ): string {
        $dateConverted = $date->format($format);

        $language = $language ?? LanguageHelper::getLanguageVersion();

        if (!$full) {
            return Carbon::parse($dateConverted)->formatLocalized('%b');
        } elseif (strtolower($case) == 'nominative') {
            return Carbon::parse($dateConverted)->formatLocalized('%B');
        } else {
            if ($language == LanguageHelper::RUSSIAN_LANGUAGE) {
                $monthsArrayGenitive = [
                    'Январь' => 'Января',
                    'Февраль' => 'Февраля',
                    'Март' => 'Марта',
                    'Апрель' => 'Апреля',
                    'Май' => 'Мая',
                    'Июнь' => 'Июня',
                    'Июль' => 'Июля',
                    'Август' => 'Августа',
                    'Сентябрь' => 'Сентября',
                    'Октябрь' => 'Октября',
                    'Ноябрь' => 'Ноября',
                    'Декабрь' => 'Декабря',
                    'January' => 'Января',
                    'February' => 'Февраля',
                    'March' => 'Марта',
                    'April' => 'Апреля',
                    'May' => 'Мая',
                    'June' => 'Июня',
                    'July' => 'Июля',
                    'August' => 'Августа',
                    'September' => 'Сентября',
                    'October' => 'Октября',
                    'November' => 'Ноября',
                    'December' => 'Декабря'
                ];

                return $monthsArrayGenitive[Carbon::parse($dateConverted)->formatLocalized('%B')]
                    ?? mb_convert_case(
                        Carbon::parse($dateConverted)->formatLocalized('%B'),
                        MB_CASE_TITLE, 'UTF-8'
                    );
            } else {
                return Carbon::parse($dateConverted)->formatLocalized('%B');
            }
        }
    }

    /**
     * Возвращает год (или два года) двух дат
     *
     * @param string|null $dateOne - Первая дата
     * @param string|null $dateTwo - Вторая дата
     * @return string|null
     */
    public static function getYearOfDates(?string $dateOne, ?string $dateTwo): ?string
    {
        /** @var string|null $result - Результат */
        $result = null;

        try {
            if ($dateOne && $dateTwo) {
                /** @var int $yearOne - Год первой даты */
                $yearOne = (new DateTime($dateOne))->format('Y');

                /** @var int $yearTwo - Год второй даты */
                $yearTwo = (new DateTime($dateTwo))->format('Y');

                if ($yearOne == $yearTwo) {
                    $result = $yearOne;
                } else {
                    $result = $yearOne . ' - ' . $yearTwo;
                }
            }
        } catch (Throwable $exception) {
            logger('common')->error(
                self::class . ': Не удалось получить годы дат. Причина: ' . $exception->getMessage()
            );
        }

        return $result;
    }

    /**
     * Возвращает диапазон дат между двумя датами включительно
     *
     * @param string $dateOne - Дата начала
     * @param string $dateTwo - Дата окончания
     * @return DatePeriod
     * @throws \Exception
     */
    public static function getDateRange(string $dateOne, string $dateTwo): DatePeriod
    {
        return new DatePeriod(
            new DateTime($dateOne),
            new DateInterval('P1D'),
            new DateTime(
                (new DateTime($dateTwo))->add(new DateInterval('P1D'))->format('d.m.Y')
            )
        );
    }

    /**
     * Возвращает дату отформатированную в формате текущих настроек языка
     *
     * @TODO По хорошему надо отказаться от этого и использовать Intl
     * @return false|string
     */
    public static function getInternationalDate($timestamp = null): string {
        $map = [
            'ru' => 'd.m.Y',
            'en' => 'd-m-Y',
            'cn' => 'Y-m-d',
        ];

        $lang = LanguageHelper::getLanguageVersion();
        if (!isset($map[$lang])) {
            $lang = 'en';
        }

        if (!$timestamp) {
            $timestamp = time();
        }

        return date($map[$lang], $timestamp);
    }
}
