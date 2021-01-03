<?php
namespace App\Models\Auxiliary\Sale;

use App\Helpers\BitrixOrderStatusConstants;
use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Arrilot\BitrixModels\Queries\D7Query;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\ORM\Fields\Relations\Reference as ReferenceField;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Sale\Internals\StatusTable;
use Exception;

/**
 * Модель для статуса заказа Битрикс.
 * Class BitrixOrderStatus
 * @package App\Models\Auxiliary\Sale
 *
 * @method static BaseQuery forOrders()
 * @method static BaseQuery forDelivery()
 */
class BitrixOrderStatus extends D7Model
{
    /** @var string Имя класса таблицы */
    public const TABLE_CLASS = StatusTable::class;

    /**
     * Возвращает идентификатор статуса.
     * @return string
     */
    public function getId(): string
    {
        return (string) $this['ID'];
    }

    /**
     * Возвращает приоритет для сортировки.
     * @return int
     */
    public function getSort(): int
    {
        return (int) $this['SORT'];
    }

    /**
     * Возвращает название статуса на текущем или заданном языке.
     * @param string|null $language
     * @return string
     */
    public function getName(string $language = null): string
    {
        return LanguageHelper::getMultilingualFieldValue($this, 'NAME', '', $language);
    }

    /**
     * Возвращает описание статуса на текущем или заданном языке.
     * @param string|null $language
     * @return string
     */
    public function getDescription(string $language = null): string
    {
        return LanguageHelper::getMultilingualFieldValue($this, 'DESCRIPTION', '', $language);
    }

    /**
     * Отбор статусов для заказов.
     * @param BaseQuery $query
     * @return BaseQuery
     */
    public static function scopeForOrders(BaseQuery $query): BaseQuery
    {
        $query->filter['TYPE'] = 'O';
        return $query;
    }

    /**
     * Отбор статусов для доставки.
     * @param BaseQuery $query
     * @return BaseQuery
     */
    public static function scopeForDelivery(BaseQuery $query): BaseQuery
    {
        $query->filter['TYPE'] = BitrixOrderStatusConstants::DELIVERY;
        return $query;
    }

    /**
     * Возвращает объект запроса.
     * @return D7Query
     */
    public static function query(): D7Query
    {
        $languages = LanguageHelper::getAvailableLanguages();
        $runtime = [];
        $select = ['*'];

        foreach ($languages as $language) {
            try {
                $upper = strtoupper($language);
                $alias = 'LANG_' . $upper;

                $field = new ReferenceField(
                    $alias,
                    StatusLangTable::class,
                    [
                        '=this.ID' => 'ref.STATUS_ID',
                        'ref.LID' => new SqlExpression('?s', $language)
                    ]
                );
                $runtime[] = $field->configureJoinType('LEFT');
                $select['NAME_' . $upper] = $alias . '.NAME';
                $select['DESCRIPTION_' . $upper] = $alias . '.DESCRIPTION';
            } catch (Exception $exception) {
            }
        }

        return parent::query()
            ->select($select)
            ->runtime($runtime);
    }
}
