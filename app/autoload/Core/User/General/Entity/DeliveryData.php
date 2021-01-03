<?php

namespace App\Core\User\General\Entity;

use stdClass;

/**
 * Класс для описания сущности "Информация о доставке"
 * Class DeliveryData
 * @package App\Core\User\General\Entity
 */
class DeliveryData
{
    /** @var string|null $ObtainingMethod - Способ получения */
    private $ObtainingMethod;

    /** @var string|null $DeliveryComment - Комментарий */
    private $DeliveryComment = null;

    public function __construct(stdClass $object = null)
    {
        if ($object) {
            $this->ObtainingMethod = $object->ObtainingMethod;
            $this->DeliveryComment = $object->DeliveryComment;
        } else {
            $this->ObtainingMethod = '21309db1-1860-45a8-a2dc-ce207453fda6';
            $this->DeliveryComment = 'коммент';
        }
    }

    /**
     * Получаем способ получения
     *
     * @return string|null
     */
    public function getObtainingMethod(): ?string
    {
        return $this->ObtainingMethod;
    }

    /**
     * Получаем комментарий доставки
     *
     * @return null|string
     */
    public function getDeliveryComment(): ?string
    {
        return $this->DeliveryComment;
    }
}
