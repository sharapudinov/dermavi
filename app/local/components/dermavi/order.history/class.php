<?php

namespace App\Local\Component;

use App\Components\ExtendedComponent;
use App\Core\Browser;
use App\Core\Sale\View\DiamondPaidServiceCollection;
use App\Core\Sale\View\OrderStatusViewModel;
use App\Core\Sale\View\OrderViewModel;
use App\Helpers\TTL;
use App\Models\Auxiliary\Sale\BitrixOrder;
use App\Models\Auxiliary\Sale\BitrixOrderStatus;
use App\Models\Catalog\PaidService;
use App\Models\User;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\DateTime;
use Illuminate\Support\Collection;

/**
 * Компонент для вывода истории заказов.
 * Class OrderHistory
 * @package App\Local\Component
 *
 * @property User $user
 * @property Collection|OrderViewModel[] $orders
 * @property bool $isIE
 */
class OrderHistory extends ExtendedComponent
{
    /** @var string Имя шаблона для юр лиц */
    const TEMPLATE_B2B = 'b2b';

    /** @var string Имя шаблона для юр лиц для списка заказов */
    const TEMPLATE_B2B_ORDER_LIST = 'include/_b2b_order_list';

    /** @var string Имя шаблона для физ лиц */
    const TEMPLATE_B2C = 'b2c';

    /** @var string Имя шаблона для пустого списка заказов */
    const TEMPLATE_EMPTY = 'empty';

    /**
     * Формат даты
     */
    const DATE_FORMAT = 'd.m.Y';

    /**
     * Столбцы списка заказов
     */
    const COLUMNS = ['num', 'date', 'status', 'quantity', 'cost'];

    /**
     * Варианты сортировки
     */
    const SORT = [
        'num'      => [
            'field' => 'ID',
        ],
        'date'     => [
            'field' => 'DATE_INSERT',
        ],
        'cost'     => [
            'field' => 'PRICE',
        ],
        'status'   => [
            'field'         => 'STATUS_ID',
            'atypical_sort' => true
        ],
        'quantity' => [
            'field'         => 'QUANTITY',
            'atypical_sort' => true
        ]
    ];

    /**
     * Иконки статусов заказа.
     */
    const STATUS_ICONS = [
        'N'  => [
            'class'      => 'clocks',
            'xlink_href' => 'clocks',
        ],
        'D'  => [
            'class'      => 'box',
            'xlink_href' => 'box',
        ],
        'F'  => [
            'class'      => 'check-round-2',
            'xlink_href' => 'check_round_2',
        ],
        'OD' => [
            'class'      => 'delivery',
            'xlink_href' => 'delivery'
        ]
    ];

    /** @var array|string[] Массив разрешенных методов для нетипичной сортировки заказов */
    private const SORT_METHODS_WHITELIST = [
        'quantitySort',
        'statusSort'
    ];

    /** @var array Параметры компонента */
    protected $params = [
        'CACHE_TIME' => ['type' => 'int', 'default' => 0],
        'B2B'        => ['type' => 'bool', 'default' => false]
    ];

    /** @var string Имя шаблона */
    private $template;

    /** @var array Фильтр заказов */
    private $filter = [];

    /** @var string Поле сортировки заказов */
    private $sortBy = 'date';

    /** @var string Направление сортировки заказов */
    private $sortOrder = 'desc';

    /**
     * Запускает компонент.
     * @throws LoaderException
     */
    public function execute(): void
    {
        $this->requireAuthorizedUser();

        Loader::includeModule('sale');
        $this->arResult['columns'] = self::COLUMNS;
        $this->arResult['sort'] = self::SORT;
        $this->applyFilter();
        $this->applySort();
        $this->addResults(['orders' => $this->cacheMethod('loadOrders')]);

        $this->addResults(['orderStatuses' => $this->cacheMethod('loadOrderStatuses')]);

        $this->arResult['isIE'] = (new Browser)->isInternetExplorer();
        $this->arResult['statusIcons'] = self::STATUS_ICONS;
        $this->arResult['forJson'] = $this->forJson($this->arResult['orders']);

        $this->includeComponentTemplate();
    }

    /**
     * Загружает заказы с учетом фильтра.
     * @return Collection|OrderViewModel[]
     */
    public function loadOrders(): Collection
    {
        $ordersQuery = BitrixOrder::forUser($this->user->getId())
            ->filter($this->filter)
            ->with('properties', 'status');

        if (!self::SORT[$this->sortBy]['atypical_sort']) {
            $ordersQuery->sort(self::SORT[$this->sortBy]['field'], $this->sortOrder);
        }


        $orders = $ordersQuery->getList();

        $orders = OrderViewModel::fromOrders($orders);


//        if (self::SORT[$this->sortBy]['atypical_sort']) {
//            $action = $this->sortBy . 'Sort';
//            if (in_array($action, self::SORT_METHODS_WHITELIST)) {
//                $orders = $this->$action($orders);
//            }
//        }

        return $orders;
    }

    /**
     * Загружает статусы заказов.
     * @return Collection|OrderStatusViewModel[]
     */
    public function loadOrderStatuses(): Collection
    {
        $orderStatuses = BitrixOrderStatus::forOrders()
            ->order('SORT')
            ->getList();

        return OrderStatusViewModel::fromOrderStatuses($orderStatuses);
    }

    /**
     * Сортирует заказы по количеству товаров в них
     *
     * @param Collection $orders - Коллекция заказов
     * @return Collection
     */
    private function quantitySort(Collection $orders): Collection
    {
        return $orders->sortBy(
            function ($order) {
                /** @var OrderViewModel $order - Заказ */
                return $order->getItems()->count();
            },
            SORT_REGULAR,
            $this->sortOrder !== 'asc'
        );
    }

    /**
     * Возвращает отсортированную по статусу коллекцию заказов
     *
     * @param Collection|OrderViewModel[] $orders Коллекция заказов
     *
     * @return Collection|OrderViewModel[]
     */
    private function statusSort(Collection $orders): Collection
    {
        return $orders->sortBy(
            function (OrderViewModel $order) {
                return $order->getStatus()->getSort();
            },
            SORT_NUMERIC,
            $this->sortOrder !== 'asc'
        );
    }

    /**
     * Применяет фильтр.
     */
    private function applyFilter(): void
    {
        $statuses = array_filter((array)$this->request->get('statuses'));
        $canceled = $this->request->get('canceled');
        $canceledValue = $canceled === 'Y' ? 'Y' : 'N';
        if ($statuses && $canceled) {
            $this->filter[] = [
                'LOGIC'     => 'OR',
                'STATUS_ID' => $statuses,
                'CANCELED'  => $canceledValue,
            ];
        } elseif ($statuses && !$canceled) {
            $this->filter['STATUS_ID'] = $statuses;
            $this->filter['CANCELED'] = 'N';
        } elseif (!$statuses && $canceled) {
            $this->filter['CANCELED'] = $canceledValue;
        }

        if ($start = $this->request->get('start_date')) {
            $this->filter['>=DATE_INSERT'] = new DateTime($start, self::DATE_FORMAT);
        }

        if ($end = $this->request->get('end_date')) {
            $this->filter['<DATE_INSERT'] = (new DateTime($end, self::DATE_FORMAT))
                ->add('23 hours + 59 minutes + 59 seconds');
        }
    }

    /**
     * Применяет сортировку.
     */
    private function applySort(): void
    {
        $sortBy = $this->request->get('sortBy');
        if ($sortBy && array_key_exists($sortBy, self::SORT)) {
            $this->sortBy = $sortBy;
        }

        if ($order = $this->request->get('order')) {
            $this->sortOrder = $order === 'asc' ? 'asc' : 'desc';
        }

        $this->arResult['sortBy'] = $this->sortBy;
        $this->arResult['sortOrder'] = $this->sortOrder;
    }

    public function forJson($orders)
    {
        $result = [];
        foreach ($this->orders as $order) {
            $result[] = [
                'id'          => $order->getId(),
                'total_price' => $order->getPrice(),
                'position_count' => count($order->getItems()->all()),
                'date'        => $order->getDate()->toString(),
                'status'      => $order->getStatus()->getName(),
                'items'       => $order->getItems()
            ];
        }

        return $result;
    }
}
