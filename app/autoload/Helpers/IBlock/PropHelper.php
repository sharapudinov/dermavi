<?php

namespace App\Helpers\IBlock;

use App\Filtration\Helper\PropertiesHelper;

/**
 * Class PropHelper
 *
 * @package App\Helpers\IBlock
 */
final class PropHelper
{
    /**
     * Возвращает id свойства по его символьному коду
     *
     * @param int $iblockId
     * @param string $propCode
     * @return int
     */
    public static function getPropertyId(int $iblockId, string $propCode): int
    {
        return PropertiesHelper::getPropertyId($iblockId, $propCode);
    }
}
