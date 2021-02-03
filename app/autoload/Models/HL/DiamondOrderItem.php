<?php
namespace App\Models\HL;

use App\Models\Auxiliary\HlD7Model;
use App\Models\Catalog\HL\CatalogClarity;
use App\Models\Catalog\HL\CatalogColor;
use App\Models\Catalog\HL\CatalogShape;
use App\Models\Catalog\HL\Quality;
use App\Models\Catalog\HL\Size;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Support\Collection;

/**
 * Класс-модель для сущности строка заказа бриллианта.
 * Class DiamondOrderItem
 * @package App\Models\HL
 *
 * @property CatalogClarity $clarity
 * @property Collection|Quality[] $qualities
 * @property Collection|CatalogShape[] $shapes
 * @property CatalogColor $color
 * @property Size $size
 *
 * @method static BaseQuery forOrder(int $orderId)
 */
class DiamondOrderItem extends HlD7Model
{
    /** @var string - Символьный код таблицы */
    protected static $tableName = 'app_diamond_order_item';

    /**
     * Возвращает идентификатор заказа.
     * @return int
     */
    public function getOrderId(): int
    {
        return (int) $this['UF_ORDER_ID'];
    }

    /**
     * Возвращает форму.
     *
     * @return string
     */
    public function getShape(): string
    {
        return $this['UF_SHAPE'];
    }

    /**
     * Возвращает огранку.
     *
     * @return string
     */
    public function getQuality(): string
    {
        return $this['UF_CUT'];
    }

    /**
     * Возвращает цвет.
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this['UF_COLOR'];
    }

    /**
     * Возвращает прозрачность.
     *
     * @return string
     */
    public function getClarity(): string
    {
        return $this['UF_CLARITY'];
    }

    /**
     * Возвращает размер.
     *
     * @return string
     */
    public function getSize(): string
    {
        return $this['UF_SIZE'];
    }

    /**
     * Возвращает запрос для получения модели заказа, которому принадлежит позиция.
     * @return BaseQuery
     */
    public function order(): BaseQuery
    {
        return $this->hasOne(DiamondOrder::class, 'ID', 'UF_ORDER_ID');
    }

    /**
     * Возвращает запрос для получения связанных форм бриллианта.
     * @return BaseQuery
     */
    public function shapes(): BaseQuery
    {
        return $this->hasMany(CatalogShape::class, 'ID', 'UF_SHAPE');
    }

    /**
     * Возвращает запрос для получения связанных видов огранки.
     * @return BaseQuery
     */
    public function qualities(): BaseQuery
    {
        return $this->hasMany(Quality::class, 'ID', 'UF_CUT');
    }

    /**
     * Возвращает запрос для получения модели цвета.
     * @return BaseQuery
     */
    public function color(): BaseQuery
    {
        return $this->hasOne(CatalogColor::class, 'ID', 'UF_COLOR');
    }

    /**
     * Возвращает запрос для получения модели размера.
     * @return BaseQuery
     */
    public function size(): BaseQuery
    {
        return $this->hasOne(Size::class, 'ID', 'UF_COLOR');
    }

    /**
     * Возвращает модель прозрачности.
     * @return BaseQuery
     */
    public function clarities(): BaseQuery
    {
        return $this->hasOne(CatalogClarity::class, 'ID', 'UF_CLARITY');
    }

    /**
     * Отбор позиций для заказа.
     * @param BaseQuery $query
     * @param int $orderId
     * @return BaseQuery
     */
    public static function scopeForOrder(BaseQuery $query, int $orderId): BaseQuery
    {
        $query->filter['UF_ORDER_ID'] = $orderId;
        return $query;
    }
}
