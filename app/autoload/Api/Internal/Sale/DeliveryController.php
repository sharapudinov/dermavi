<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\Delivery\DeliveryCalculator;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Helpers\NumberHelper;
use App\Helpers\PriceHelper;
use App\Helpers\UserCartHelper;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы со службами доставки
 * Class DeliveryController
 *
 * @package App\Api\Internal\Sale
 */
class DeliveryController extends BaseController
{
    /**
     * Возвращает расчитанную стоимость и срок доставки
     *
     * @return ResponseInterface
     */
    public function getCalculatedPrice(): ResponseInterface
    {
        /** @var array|string[] $request Массив, описывающий параметры запроса */
        $request = $this->request->getQueryParams();

        try {
            /** @var \Bitrix\Sale\Delivery\CalculationResult $deliveryInfo Объект, описывающий информацию о доставке */
            $deliveryInfo = (new DeliveryCalculator())->calculateConcrete(
                UserCart::getUserCart(new DefaultCartType())->getPrice(),
                $request['city'],
                $request['to_door'] === 'true'
            );

            /** @var CurrencyEntity $currency Объект, описывающий валюту */
            $currency = Currency::getCurrentCurrency();

            $cartInfo = UserCartHelper::loadCartInfo(new DefaultCartType());
            $deliveryPrice = ceil(PriceHelper::getPriceInSpecificCurrency(
                $deliveryInfo->getDeliveryPrice(),
                $currency
            ));

            $totalPrice = ceil($cartInfo['float_cart_price'] + $cartInfo['cart_service_cost'] + $deliveryPrice);

            return $this->respondWithSuccess([
                'price' => NumberHelper::transformNumberToPrice($deliveryPrice) . ' ' . $currency->getSymbol(),
                'total_price' => NumberHelper::transformNumberToPrice($totalPrice) . ' ' . $currency->getSymbol(),
                'cart_service_cost' => NumberHelper::transformNumberToPrice($cartInfo['cart_service_cost']) . ' ' . $currency->getSymbol(),
                'days' => $deliveryInfo->getPeriodDescription()
            ]);
        } catch (Exception | Throwable $exception) {
            return $this->respondWithError([
                'delivery' => false
            ]);
        }
    }
}
