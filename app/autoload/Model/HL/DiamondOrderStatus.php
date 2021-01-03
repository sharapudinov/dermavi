<?php
namespace App\Models\HL;

use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Models\Auxiliary\HlD7Model;
use Illuminate\Support\Collection;
use LogicException;

/**
 * Класс-модель для сущности Статус заказа бриллианта.
 * Class DiamondOrderStatus
 * @package App\Models\HL
 */
class DiamondOrderStatus extends HlD7Model
{
    /** @var string Код статуса "Заказ позиции" */
    public const NEW = 'NEW';

    /** @var string Код статуса "Оценка работы" */
    public const EVALUATION = 'EVALUATION';

    /** @var string Код статуса "Оплата" */
    public const PAYMENT = 'PAYMENT';

    /** @var string Код статуса "Начало обработки" */
    public const PROCESS = 'PROCESS';

    /** @var string Код статуса "Отправка изделий" */
    public const SHIPPING = 'SHIPPING';

    /** @var string Код статуса "Завершено" */
    public const FINISHED = 'FINISHED';

    /** @var string - Символьный код таблицы */
    protected static $tableName = 'app_diamond_order_status';

    /** @var Collection|static[] */
    private static $instances;

    /**
     * Получает идентификатор статуса
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['ID'];
    }

    /**
     * Возвращает код статуса.
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this['UF_XML_ID'];
    }

    /**
     * Возвращает название статуса на заданном языке.
     * @param string $lang
     * @return string
     */
    public function getName(string $lang = null): string
    {
        return (string) LanguageHelper::getHlMultilingualFieldValue($this, 'NAME', $lang);
    }

    /**
     * Возвращает представление статуса по умолчанию.
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * Ищет и возвращает модель для заданного кода статуса.
     * @param string $code
     * @return static
     */
    public static function getByCode(string $code): self
    {
        $result = self::getAll()[$code];
        if (!$result) {
            throw new LogicException("Diamond order status $code is not registered");
        }
        return $result;
    }

    /**
     * Возвращает все зарегистрированные статусы.
     * @return Collection|static[]
     */
    private static function getAll(): Collection
    {
        if (self::$instances !== null) {
            return self::$instances;
        }

        return self::$instances = self::cache(TTL::DAY)
            ->keyBy('UF_XML_ID')
            ->getList();
    }
}