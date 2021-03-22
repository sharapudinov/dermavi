<?php

namespace App\Core\Traits\Sale;

use App\Helpers\NumberHelper;
use Illuminate\Support\Collection;

/**
 * Класс-трейт для работы с отложенными товарами
 * Trait StashedProductTrait
 * @package App\Core\Traits\Sale
 */
trait StashedProductTrait
{
    /** @var string $checkedProductsCookie - Кука для выбранных для корзины на других страницах товаров */
    private $checkedProductsCookie = 'prepared_for_cart_products';

    /**
     * Получаем информацию о выбранных для добавления в корзину товарах
     *
     * @return array
     */
    protected function getStashedForCartProductsInfo(): array
    {
        /** @var array $checkedProducts - Массив объектов выбранных для добавления в корзину товаров */
        $checkedProducts = json_decode($_COOKIE[$this->checkedProductsCookie]);
        $checkedProductsNew = [];
        $checkedProductsCost = 0;
        foreach ($checkedProducts as $checkedProduct) {
            $checkedProductsNew[$checkedProduct->id] = $checkedProduct;
            $checkedProductsCost += $checkedProduct->price;
        }

        return [
            'products' => $checkedProductsNew,
            'totalCost' => NumberHelper::transformNumberToPrice($checkedProductsCost)
        ];
    }
}
