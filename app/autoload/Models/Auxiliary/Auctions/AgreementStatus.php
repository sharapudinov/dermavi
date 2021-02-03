<?php

namespace App\Models\Auxiliary\Auctions;

use Arrilot\BitrixModels\Models\EloquentModel;

/**
 * Класс-модель для описания сущности "Статус согласия переторжки"
 * Class AgreementStatus
 *
 * @package App\Models\Auxiliary\Auctions
 */
class AgreementStatus extends EloquentModel
{
    /** @var string - Статус повышения ставки */
    public const INCREASE_STATUS = 'Увеличение';

    /** @var string - Статус сохранения ставки */
    public const LEAVE_STATUS = 'Сохранение';

    /** @var string $table - Символьный код таблицы */
    protected $table = 'app_rebidding_agreement_status';

    /** @var array|string[] $fillable - Массив полей, допущенных до заполнения */
    protected $fillable = ['name'];

    /** @var bool $timestamps - Флаг использования полей с датой/временем */
    public $timestamps = false;

    /**
     * Возвращает идентификатор статуса
     *
     * @return int
     */
    public function getId(): int
    {
        return $this['id'];
    }

    /**
     * Возвращает название статуса
     *
     * @return string
     */
    public function getName(): string
    {
        return $this['name'];
    }
}
