<?php

namespace App\Core\BitrixEvent;

use App\Core\BitrixEvent\Entity\EventMessage as EventMessageEntity;
use CEventMessage;
use Illuminate\Support\Collection;

/**
 * Класс для работы с почтовыми событиями
 * Class EventMessage
 * @package App\Core\BitrixEvent
 */
class EventMessage extends CEventMessage
{
    /**
     * Получаем информацию по событию по символьному коду
     *
     * @param string $code - Символьный код почтового события
     * @param string|null $language - Язык, на котором нужно отослать шаблон
     * @return mixed
     */
    public static function getEventMessagesByCode(string $code, string $language = null): Collection
    {
        $orderBy = 'sort';
        $orderDirection = 'asc';
        $filter = ['TYPE' => $code];
        if ($language) {
            $filter['LANGUAGE_ID'] = $language;
        }

        $info = parent::GetList($orderBy, $orderDirection, $filter);
        $collection = collect([]);
        while ($eventMessage = $info->GetNext()) {
            $collection->push(new EventMessageEntity($eventMessage));
        }

        return $collection;
    }
}
