<?php

namespace App\Filtration\Enum;

/**
 * Class RequestEnum
 *
 * @package App\Filtration\Enum
 */
final class RequestEnum
{
    /** @var string Имя поля запроса, передающее значение диапазона "от" */
    public const RANGE_FIELD_FROM = 'from';

    /** @var string Имя поля запроса, передающее значение диапазона "до" */
    public const RANGE_FIELD_TO = 'to';
}
