<?php

namespace App\Core\Catalog\ViewedProducts;

/**
 * Интерфейс, описывающий методы для работы с просмотренными товарами
 * Interface ViewedProductTypeInterface
 *
 * @package App\Core\Catalog\ViewedProducts
 */
interface ViewedProductTypeInterface
{
    /**
     * Возвращает идентификатор типа просмотренного товара
     *
     * @return string
     */
    public function getType(): string;
}
