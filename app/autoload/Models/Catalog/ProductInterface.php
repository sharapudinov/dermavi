<?php

namespace App\Models\Catalog;

/**
 * Interface ProductInterface
 *
 * @package App\Models\Auxiliary
 */
interface ProductInterface
{

    /**
     * ID Товара
     * @return int
     */
    public function getID(): int;

    /**
     * Цена в корзине (в рублях)
     * @return float
     */
    public function getPriceForCart(): float;

    /**
     * Возвращает символьный код.
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Код типа товара
     * @return string
     */
    public function getProductTypeCode(): string;
}
