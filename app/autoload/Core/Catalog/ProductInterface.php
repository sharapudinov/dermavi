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
     * Проверяет доступен ли товар для продажи
     *
     * @return bool
     */
    public function isAvailableForSelling(): bool;

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
     * Общий вес изделия
     * @return string
     */
    public function getWeight(): string;

    /**
     * Наличие GIA сертификата
     *
     * @return boolean
     */
    public function hasGIACert(): bool;

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
