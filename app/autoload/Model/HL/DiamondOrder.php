<?php
namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;
use App\Models\User;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Bitrix\Main\Type\Date;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности Заказ бриллианта.
 * @package App\Models\HL
 *
 * @property DiamondOrderStatus $status
 * @property Collection|DiamondOrderItem[] $items
 *
 * @method static BaseQuery forUser(int $userId)
 */
class DiamondOrder extends HlD7Model
{
    /** @var string - InitDir для кеширования бриллиантов под заказ */
    public const DIAMOND_ORDER_CACHE_INIT_DIR = 'DiamondOrder_';

    /** @var string - Символьный код таблицы */
    protected static $tableName = 'app_diamond_order';

    /**
     * Возвращает дату заказа.
     * @return string
     */
    public function getDate(): string
    {
        return $this['UF_DATE']->format('d.m.Y');
    }

    /**
     * Возвращает цену заказа.
     * @return float
     */
    public function getPrice(): ?float
    {
        return (float) explode('|', $this['UF_PRICE'])[0];
    }

    /**
     * Возвращает номер телефона, указанный при заказе.
     * @return string
     */
    public function getPhone(): string
    {
        return $this['UF_PHONE'];
    }

    /**
     * Возвращает email, указанный при заказе.
     * @return string
     */
    public function getEmail(): string
    {
        return $this['UF_EMAIL'];
    }

    /**
     * Возвращает текст сообщения, заданный при оформлении заказа.
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this['UF_MESSAGE'];
    }

    /**
     * Возвращает наименование компании заказчика.
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this['UF_COMPANY'];
    }

    /**
     * Возвращает ФИО контактного лица.
     * @return string|null
     */
    public function getContactPerson(): ?string
    {
        return $this['UF_CONTACT_PERSON'];
    }

    /**
     * Получает массив идентификаторов прикрепленных файлов
     *
     * @return array|null
     */
    public function getFiles(): ?array
    {
        return $this['UF_FILE'];
    }

    /**
     * Получает пользователя, оформлявшего заказ
     *
     * @return BaseQuery
     */
    public function user(): BaseQuery
    {
        return $this->hasOne(User::class, 'ID', 'UF_USER_ID');
    }

    /**
     * Возвращает запрос для получения модели статуса заказа.
     * @return BaseQuery
     */
    public function status(): BaseQuery
    {
        return $this->hasOne(DiamondOrderStatus::class, 'ID', 'UF_STATUS');
    }

    /**
     * Возвращает запрос для получения позиций заказа.
     * @return BaseQuery
     */
    public function items(): BaseQuery
    {
        return $this->hasMany(DiamondOrderItem::class, 'UF_ORDER_ID', 'ID');
    }
}
