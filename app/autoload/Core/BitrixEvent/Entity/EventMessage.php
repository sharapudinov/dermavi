<?php

namespace App\Core\BitrixEvent\Entity;

/**
 * Класс для описания сущности "Почтовое событие"
 * Class EventMessage
 * @package App\Core\BitrixEvent\Entity
 */
class EventMessage
{
    /** @var int $messageId - Идентификатор сообщения */
    private $messageId;

    /** @var string $eventName - Символьный код почтового события */
    private $eventName;

    /** @var string $languageId Идентификатор языка письма */
    private $languageId;

    /**
     * EventMessage constructor.
     * @param array $eventMessage
     */
    public function __construct(array $eventMessage)
    {
        $this->messageId = $eventMessage['ID'];
        $this->eventName = $eventMessage['EVENT_NAME'];
        $this->languageId = $eventMessage['LANGUAGE_ID'];
    }

    /**
     * Получаем идентикатор почтового события
     *
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * Получает символьный код почтового события
     *
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * Возвращает идентификатор языка письма
     *
     * @return string
     */
    public function getLanguageId(): string
    {
        return $this->languageId;
    }
}
