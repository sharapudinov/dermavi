<?php
namespace App\Core\Sale\View;

use App\Models\Auxiliary\Sale\BitrixOrderStatus;
use Illuminate\Support\Collection;

/**
 * Класс для модели представления статуса заказа.
 * Class OrderStatusViewModel
 *
 * @package App\Core\Sale\View
 */
class OrderStatusViewModel
{
    /** @var string Статус отправленного заказа */
    public const STATUS_IN_PROCESS = 'N';

    /** @var string Статус выполненного заказа */
    public const STATUS_FINISHED = 'F';

    /** @var string Идентификатор статуса */
    private $statusId;

    /** @var int Приоритет сортировки */
    private $sort;

    /** @var string Наименование статуса */
    private $name;

    /** @var string Описание статуса */
    private $description;

    /**
     * OrderStatusViewModel constructor.
     *
     * @param BitrixOrderStatus $source Модель статуса
     */
    public function __construct(BitrixOrderStatus $source)
    {
        $this->statusId = $source->getId();
        $this->sort = $source->getSort();
        $this->name = $source->getName();
        $this->description = $source->getDescription();
    }

    /**
     * Возвращает наименование.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает описание.
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;

    }

    /**
     * Возвращает идентификатор статуса.
     * @return string
     */
    public function getStatusId(): string
    {
        return $this->statusId;
    }

    /**
     * Возвращает приоритет сортировки.
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Это статус выполненного заказа?
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->getStatusId() === self::STATUS_FINISHED;
    }

    /**
     * Возвращает флаг, указывающий на то, что заказ имеет статус "В обработке"
     *
     * @return bool
     */
    public function isInProcess(): bool
    {
        return $this->getStatusId() === self::STATUS_IN_PROCESS;
    }
    
    /**
     * Возвращает коллекцию заполненных моделей для заданных статусов заказов.
     * @param Collection|BitrixOrderStatus[] $orderStatuses - статусы заказов, ключом коллекции должен выступать ID
     * @return Collection|self[]
     */
    public static function fromOrderStatuses(Collection $orderStatuses): Collection
    {
        return $orderStatuses->map(function (BitrixOrderStatus $orderStatus) {
            return new self($orderStatus);
        });
    }
}
