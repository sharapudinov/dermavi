<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\Sale\Payture\Cheque\RefundCheque;
use App\Core\Sale\Payture\PaytureInPay;
use App\Core\Sale\PaytureService;
use App\Models\Auxiliary\Sale\BitrixOrder;
use Bitrix\Main\Loader;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use Bitrix\Sale\Shipment;
use Bitrix\Sale\ShipmentCollection;
use Bitrix\Sale\ShipmentItem;
use Bitrix\Sale\ShipmentItemCollection;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс-контроллер для работы с оплатами
 * Class PaymentController
 *
 * @package App\Api\Internal\Sale
 */
class RefundController extends BaseController
{
    /**
     * Загружаем пункты самовывоза для карты
     *
     * @return ResponseInterface
     * @throws \Bitrix\Main\LoaderException
     */
    public function refund($orderId): ResponseInterface
    {
        if (!user() || !user()->isAdmin()) {
            return $this->errorUnauthorized();
        }
        Loader::includeModule('sale');
        $formValues = $this->request->getParsedBody();

        $order = BitrixOrder::getById($orderId);
        if (!$order) {
            return $this->respondWithError('Order not found');
        }

        $allItems = $this->getOrderItems($order);

        $totalSum   = 0;
        $result     = false;
        $chequeInfo = [
            'Positions'       => [],
            'CustomerContact' => $order->getEmail(),
        ];

        $idsToRefund = [];
        foreach ($allItems as $item) {
            if (!isset($formValues[$item['key']])) {
                continue;
            }

            $totalSum      += $item['price'];
            $idsToRefund[] = $item['key'];
        }

        if (!$idsToRefund) {
            return $this->respondWithError('Не выбраны продукты для возврата');
        }

        PaytureService::_init();
        $result = PaytureInPay::Refund($formValues['password'], $orderId, $totalSum * 100);

        if ($result->Success) {
            $chequeResult = (new RefundCheque($order, $idsToRefund))->send();

            if (!$chequeResult) {
                return $this->respondWithError('Ошибка при отправке чека');
            }

            $this->removeItemsFromOrder($orderId, $idsToRefund);

            $this->updatePayment($order, $totalSum);
        } else {
            logger('cheque')->error('Ошибка при возврате платежа '.implode(',', get_object_vars($result)));
            return $this->respondWithError('Ошибка при возврате платежа');
        }

        return $this->response->withJSON(true);
    }

    /**
     * @return ResponseInterface
     * @throws \Bitrix\Main\LoaderException
     */
    public function getItems($orderId): ResponseInterface
    {
        if (!user() || !user()->isAdmin()) {
            return $this->errorUnauthorized();
        }

        Loader::includeModule('sale');

        /** @var BitrixOrder $order */
        $order = BitrixOrder::getById($orderId);
        if (!$order) {
            return $this->respondWithError('Order not found');
        }

        $result = $this->getOrderItems($order);

        return $this->response->withJSON($result);
    }

    public function updatePayment(BitrixOrder $order, $totalSum)
    {
        //Обновляем платежку
        $fields = [
            'SUM' => $order->payment->getSum() - $totalSum,
        ];

        if ($order->getPriceInRub() == $totalSum) {
            $fields['PAID']      = 'N';
            $fields['IS_RETURN'] = 'Y';
        }

        $order->update([
           'SUM_PAID' => $order->getSumPaid()-$totalSum
        ]);

        $order->payment->update($fields);
    }

    /**
     * @param BitrixOrder $order
     *
     * @return array
     */
    protected function getOrderItems(BitrixOrder $order): array
    {
        $result = [];
        foreach ($order->items as $item) {
            $result[] = [
                'key'   => $item->getId(),
                'name'  => PaytureService::getItemName($item),
                'price' => $item->getPrice() * $item->getQuantity(),
            ];
        }

        if ($order->getPriceDelivery()) {
            $result[] = [
                'key'   => 'delivery',
                'name'  => 'Доставка',
                'price' => $order->getPriceDelivery(),
            ];
        }

        return $result;
    }

    /**
     * @param       $orderId
     * @param array $idsToRefund
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    protected function removeItemsFromOrder($orderId, array $idsToRefund): void
    {
        $bitrixOrder = Order::load($orderId);
        /** @var Shipment[]|ShipmentCollection $collection */
        $collection = $bitrixOrder->getShipmentCollection();
        foreach ($collection as $shipment) {
            if ($shipment->isSystem()) {
                continue;
            }
            /** @var $items ShipmentItem[]|ShipmentItemCollection $item */
            $items = $shipment->getShipmentItemCollection();

            //Удаляем возвращаемые товары из отгрузки
            foreach ($items as $item) {
                if (in_array($item->getBasketItem()->getId(), $idsToRefund)) {
                    $item->delete();
                    break;
                }
            }

            //Ставим стоимость доставки - 0
            if (in_array('delivery', $idsToRefund)) {
                $shipment->setBasePriceDelivery(0, true);
            }
        }

        $bitrixOrder->save();
        $bitrixOrder->refreshData();

        $basket = $bitrixOrder->getBasket();
        /** @var BasketItem[] $items */
        $items = $basket->getBasketItems();

        //Удаляем из самого заказа
        foreach ($items as $item) {
            if (in_array($item->getId(), $idsToRefund)) {
                $item->delete();
            }
        }

        $bitrixOrder->save();
    }
}
