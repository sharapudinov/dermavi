<?php

namespace App\Filtration\Interfaces;

use Bitrix\Main\ORM\Query\Query;
use Throwable;

/**
 * Interface FilterItemQueryInterface
 * Элемент фильтра в виде объекта Query
 *
 * @package App\Filtration\Interfaces
 */
interface FilterItemQueryInterface extends FilterItemInterface
{
    /**
     * Возвращает объект Query с добавленными параметрами фильтрации по полю
     *
     * @param null|Query $query
     * @return Query
     * @throws Throwable
     */
    public function getValue($query = null): Query;
}
