<?php

namespace App\Models\Auxiliary\Auctions;

use Arrilot\BitrixModels\Models\EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Класс-модель для описания сущности "Согласие пользователя на переторжку"
 * Class UserRebiddingAgreement
 *
 * @package App\Models\Auxiliary\Auctions
 *
 * @property-read AgreementStatus $status
 */
class UserRebiddingAgreement extends EloquentModel
{
    /** @var string - Символьный код столбца времени создания записи */
    public const CREATED_AT = 'created_at';

    /** @var string - Символьный код столбца времени обновления записи */
    public const UPDATED_AT = 'updated_at';

    /** @var string $table - Символьный код таблицы */
    protected $table = 'app_user_rebidding_agreement';

    /** @var array|string[] $fillable - Массив полей, допущенных до заполнения */
    protected $fillable = ['auction_lot_id', 'user_id', 'status_id'];

    /** @var string $primaryKey - Поле PK */
    protected $primaryKey = 'id';

    /**
     * Возвращает идентификатор лота
     *
     * @return int
     */
    public function getLotId(): int
    {
        return $this['auction_lot_id'];
    }

    /**
     * Возвращает идентификатор пользователя
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this['user_id'];
    }

    /**
     * Возвращает модель статуса участия в переторжке
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(
            '\App\Models\Auxiliary\Auctions\AgreementStatus',
            'status_id',
            'id'
        );
    }
}
