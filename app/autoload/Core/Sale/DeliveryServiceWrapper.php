<?php
namespace App\Core\Sale;

use App\Core\SprintOptions\OrderSettings;
use Bitrix\Sale\Delivery\Services\Base;
use Bitrix\Sale\Delivery\Services\EmptyDeliveryService;
use Bitrix\Sale\Delivery\Services\Manager;
use Exception;
use Illuminate\Support\Collection;
use LogicException;

/**
 * Класс для работы со службами доставки.
 * Class Delivery
 * @package App\Core\Sale
 */
class DeliveryServiceWrapper
{
    /** @var string Код службы доставки "Самовывоз" */
    public const PICKUP = 'pickup';

    /** @var string Код службы доставки "Спецсвязь" */
    public const CCCB = 'cccb';

    /** @var string Код для заглушки */
    private const EMPTY = 'empty';

    /** @var Collection|DeliveryServiceWrapper[] Доступные службы доставки */
    private static $instances;

    /** @var DeliveryServiceWrapper Пустая служба доставки */
    private static $empty;

    /** @var string Код службы доставки */
    private $code;

    /** @var Base Служба доставки */
    private $service;

    /**
     * Delivery constructor.
     * @param string $code
     * @param Base $service
     */
    public function __construct(string $code, Base $service)
    {
        $this->code = $code;
        $this->service = $service;
    }

    /**
     * Возвращает код службы доставки.
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Возвращает объект службы доставки.
     * @return Base
     */
    public function getService(): Base
    {
        return $this->service;
    }

    /**
     * Возвращает идентификатор службы доставки.
     * @return int
     */
    public function getId(): int
    {
        return $this->service->getId();
    }

    /**
     * Возвращает наименование службы доставки, как оно забито в админке.
     * @return string
     */
    public function getName(): string
    {
        return $this->service->getName();
    }

    /**
     * Возвращает настроенные и доступные службы доставки.
     * @return Collection|DeliveryServiceWrapper[]
     */
    public static function getAvailable(): Collection
    {
        if (static::$instances === null) {
            static::$instances = collect();
            static::rememberService(static::PICKUP, 3);
            static::rememberService(static::CCCB, 2);
        }
        return collect(static::$instances);
    }

    /**
     * Возвращает обертку для службы доставки по коду.
     * @param string $code
     * @return static|null
     */
    public static function findByCode(string $code): ?self
    {
        return static::getAvailable()[$code];
    }

    /**
     * Ищет службу доставки по идентификатору.
     * @param int $id
     * @return static|null
     */
    public static function findById(int $id): ?self
    {
        return static::getAvailable()->first(function (DeliveryServiceWrapper $wrapper) use ($id) {
            return $wrapper->getId() == $id;
        });
    }

    /**
     * Возвращает пустую службу доставки, используемую в качестве заглушки.
     * @return DeliveryServiceWrapper
     */
    public static function getEmptyDelivery(): self
    {
        if (static::$empty === null) {
            try {
                static::$empty = new static(static::EMPTY, Manager::getObjectById(EmptyDeliveryService::getEmptyDeliveryServiceId()));
            } catch (Exception $e) {
                throw new LogicException("Что-то не настроено");
            }
        }
        return static::$empty;
    }

    /**
     * Загружает службу доставки.
     * @param string $code
     */
    private static function rememberService(string $code, int $serviceId): void
    {
        try {
            $service = Manager::getObjectById($serviceId);
            static::$instances[$code] = new static($code, $service);
        } catch (Exception $e) {
            // Does nothing
        }
    }
}
