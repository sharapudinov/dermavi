<?php

namespace App\Core\Traits;

use App\Core\Catalog\FilterFields\ProductFilterFields;

/**
 * Трейт для работы со спиком товаров
 * Trait ProductsListTrait
 * @package App\Core\Traits
 */
trait ProductsListTrait
{
    /** @var string $emptyTemplate - Название шаблона для пустой товарной выдачи */
    private $emptyTemplate = 'empty';

    /** @var int $page - Номер страницы */
    private $page = null;

    /** @var string $sortBy - Поле для сортировки */
    private $sortBy = null;

    /** @var string $order - Порядок сортировки */
    private $order=null;

    /** @var int $isShowMore -  идентификатор кнопки показать больше */
    private $isShowMore=null;

    /** @var array|int[] $sortTypes - Массив, описывающий константы для типов сортировок для полей */
    private $sortTypes = [
        'lot' => SORT_NATURAL,
        'id'  => SORT_NATURAL
    ];

    /** @var string $propertyPrefix Префикс свойства (в зависимости от того, происходит ли работа с ИБ или ХЛ) */
    private $propertyPrefix = 'PROPERTY';

    /**
     * Загружает параметры
     *
     * @return void
     */
    protected function loadParams(): void
    {
        if ($this->request->get('p')) {
            $this->page = (int)$this->request->get('p');
        }
        if ($this->request->get('sortBy')) {
            $this->sortBy = (string)$this->request->get('sortBy');
        }
        if ($this->request->get('order')) {
            $this->order = (string)$this->request->get('order');
        }
        if ($this->request->get('isShowMore')) {
            $this->isShowMore = $this->request->get('isShowMore');
        }

        if (!$this->page) {
            $this->page = 1;
        }
    }

    /**
     * Возвращает номер страницы
     *
     * @return int
     */
    protected function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * Возвращает поле сортировки
     *
     * @return string
     */
    protected function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * Возвращает порядок сортировки
     *
     * @return string
     */
    protected function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * Возвращает хеш фильтра
     *
     * @param array|mixed[] $filter - Фильтр для запроса бриллиантов
     *
     * @return string
     */
    protected function getFilterHash(array $filter = null): string
    {
        return md5(json_encode($filter ?? ProductFilterFields::getFilterForCatalog()));
    }

    /**
     * Возвращает направление сортировки
     *
     * @param string $param - Параметр
     * @return string
     */
    protected function getSortOrder(string $param): string
    {
        return strtoupper($param) == "DESC" ? "DESC" : "ASC";
    }

    /**
     * Проверяет сортировку на направление по возрастанию
     *
     * @return bool
     */
    protected function isAscendingSort(): bool
    {
        return $this->getOrder() == 'asc';
    }

    /**
     * Возвращает поле для сортировки
     *
     * @param string $param - Параметр
     * @return string|null
     */
    protected function getSortField(string $param): ?string
    {
        $data = [
            "price" => 'PROPERTY_PRICE',
            "added" => "DATE_CREATE",
            "id"    => "CODE",
        ];

        return $data[$param];
    }

    /**
     * Трансформирует символьный код поля до необходимого (если свойство, то добавляется _VALUE)
     *
     * @param string $propertyCode - Символьный код свойства
     *
     * @return string
     */
    protected function transformPropertyToFullCode(string $propertyCode): string
    {
        return strstr($propertyCode, 'PROPERTY') ? $propertyCode . '_VALUE' : $propertyCode;
    }

    /**
     * Выбирает шаблон компонента
     *
     * @param int $productsCount - Количество товаров
     * @param string|null $template - Ссылка на переменную, содержащую название шаблона
     * @param callable $callback - Коллбэк функция
     *
     * @return void
     */
    protected function chooseTemplate(int $productsCount, ?string &$template, callable $callback): void
    {
        if ($productsCount == 0) {
            $template = $this->emptyTemplate;
        } else {
            if (is_ajax()) {
                $callback($template);
            }
        }
    }

    /**
     * Возвращает тип сортировки для текущего поля сортировки
     *
     * @return int
     */
    protected function getSortType(): int
    {
        return $this->sortTypes[$this->getSortBy()] ?? SORT_REGULAR;
    }
}
