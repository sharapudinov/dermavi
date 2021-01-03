<?php

namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;
use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\DateTime;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности "Обший лог"
 * Class Address
 *
 * @package App\Models\HL
 */
class CommonLog extends HlD7Model
{
    /** @var string Название таблицы в БД */
    public const TABLE_CODE = 'app_common_log';

    /**
     * CommonLog constructor.
     * @param null $id
     * @param null $fields
     * @throws ObjectException
     */
    public function __construct($id = null, $fields = null)
    {
        parent::__construct($id, $fields);
    }

    /**
     * Возвращает класс таблицы
     *
     * @return string
     */
    public static function tableClass(): string
    {
        return highloadblock_class(self::TABLE_CODE);
    }

    /**
     * Получить коллекцию записей лога по типу сущности, её id и типу сообщения
     *
     * @param string|array $entityType
     * @param int|null $entityId
     * @param string|array|null $messageType
     *
     * @return Collection|self[]
     */
    public static function getByEntity(
        $entityType,
        ?int $entityId = null,
        $messageType = null
    ): Collection {
        $filter = [
            '=UF_ENTITY_TYPE' => $entityType,
        ];

        if ($entityId) {
            $filter['=UF_ENTITY_ID'] = $entityId;
        }

        if ($messageType) {
            $filter['=UF_MESSAGE_TYPE'] = $messageType;
        }

        return self::query()->filter($filter)->getList();
    }

    public function getDateCreate(): DateTime
    {
        return $this['UF_DATE_CREATE'];
    }

    public function getEntityType(): string
    {
        return $this['UF_ENTITY_TYPE'];
    }

    public function getEntityId(): ?int
    {
        return isset($this['UF_ENTITY_ID']) && (int)$this['UF_ENTITY_ID'] > 0 ? (int)$this['UF_ENTITY_ID'] : null;
    }

    public function getMessageType(): string
    {
        return $this['UF_MESSAGE_TYPE'];
    }

    public function getMessage(): string
    {
        return !empty($this['UF_MESSAGE']) ? $this['UF_MESSAGE'] : '';
    }

    public function getAdditionalData(): ?array
    {
        return !empty($this['UF_ADDITIONAL_DATA'])
            ? json_decode($this['UF_ADDITIONAL_DATA'], true)
            : null;
    }

    /**
     * Добавляет запись в общий лог
     *
     * @param string $entityType
     * @param int|null $entityId
     * @param string $messageType
     * @param string $message
     * @param array|null $additionalData
     *
     * @throws ObjectException
     *
     * @return static|bool
     */
    public static function add(
        string $entityType,
        ?int $entityId,
        string $messageType,
        string $message,
        ?array $additionalData
    ) {
        return self::create([
            'UF_DATE_CREATE' => new DateTime(),
            'UF_ENTITY_TYPE' => $entityType,
            'UF_ENTITY_ID' => $entityId,
            'UF_MESSAGE_TYPE' => $messageType,
            'UF_MESSAGE' => $message,
            'UF_ADDITIONAL_DATA' => $additionalData
                ? json_encode($additionalData, JSON_UNESCAPED_UNICODE)
                : null,
        ]);
    }
}
