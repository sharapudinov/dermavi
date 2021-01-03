<?php

namespace App\Filtration\Enum;

/**
 * Class ParamsEnum
 *
 * @package App\Filtration\Enum
 */
final class ParamsEnum
{
    /** @var int Лимит выборки связанных данных за один запрос */
    public const QUERY_CHUNK_SIZE = 200;
}
