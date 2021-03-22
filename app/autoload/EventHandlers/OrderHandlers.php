<?php

namespace App\EventHandlers;

use App\Core\BitrixEvent\EventMessage;
use App\Core\BitrixProperty\Property;
use App\Core\Email;
use App\Core\Memcache\MemcacheInitializer;
use App\Core\Sale\Entity\CartType\CartTypeInterface;
use App\Core\Sale\OrderMail\ProductOrderInterface;
use App\Core\Sale\Payture\Cheque\FullPaymentCheque;
use App\Core\Sale\View\OrderViewModel;
use App\Helpers\JewelryConstructorHelper;
use App\Helpers\JewelryHelper;
use App\Helpers\SiteHelper;
use App\Models\Auxiliary\Sale\BitrixOrder;
use App\Models\Catalog\Diamond;
use App\Models\Catalog\ProductInterface;
use App\Models\Jewelry\HL\JewelryBlankDiamonds;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\Jewelry\JewelrySku;
use App\Models\Search\ElasticService;
use Arrilot\BitrixCacher\Cache;
use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use CEvent;
use Exception;
use Illuminate\Support\Collection;
use Throwable;
use Bitrix\Sale\Order;
use Bitrix\Sale\BasketItem;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Web\Json;

/**
 * Класс-обработчик заказов
 * Class OrderHandlers
 * @package App\EventHandlers
 */
class OrderHandlers
{
    /**
     * Отправляем сообщения пользователю и менеджерам после оформления(создания) заказа
     *
     * @param int               $orderId
     * @param CartTypeInterface $cartType Тип корзины
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @return void
     *
     */
    public static function sendMessagesAfterOrderCreate(int $orderId, CartTypeInterface $cartType): void
    {
        Loader::includeModule('sale');

        /** @var BitrixOrder $order Модель заказа */
        $order = BitrixOrder::filter(['ID' => $orderId])->with('properties')->getList()->first();

        /** @var ProductOrderInterface $mode Тип обработчика письма заказа */
        $mode   = $order->getMode();
        $fields = [
            'ORDER_ID' => $orderId,
            'MODE'     => get_class($mode),
        ];

        foreach ($mode->getMailEventName() as $type) {
            /** @var \App\Core\BitrixEvent\Entity\EventMessage $eventMessage Почтовое событие */
            $eventMessage = EventMessage::getEventMessagesByCode($type)->first();

            CEvent::SendImmediate(
                $type,
                's2',
                $fields,
                'Y',
                $eventMessage->getMessageId(),
                [],
                'ru'
            );
        }

        /** @var string $eventMessageUserCode - Код типа почтового события для отправки пользователю */
        $eventMessageUserCode = 'SALE_NEW_ORDER';

        $userLanguageInfo = [
            'language_id' => 'ru'
        ];
        /** @var \App\Core\BitrixEvent\Entity\EventMessage $eventMessage - Коллекция почтовых событий */
        $eventMessage = EventMessage::getEventMessagesByCode(
            $eventMessageUserCode,
            $userLanguageInfo['language_id']
        )->first();

        $fields['EMAIL_TO']       = $order->user->getEmail();
        $fields['COMPONENT_NAME'] = $cartType->getMailComponentName();

        CEvent::SendImmediate(
            $eventMessageUserCode,
            $userLanguageInfo['site_id'],
            $fields,
            'Y',
            $eventMessage->getMessageId(),
            [],
            $userLanguageInfo['language_id']
        );
    }

    /**
     * Отправляет письмо менеджеру при обновлении состава заказа
     *
     * @param BitrixOrder $bitrixOrder - Обновленный заказ
     * @param string      $update      - Что изменилось в заказе
     *
     * @return void
     */
    public static function notifyManagerAboutOrderUpdate(BitrixOrder $bitrixOrder, string $update): void
    {
        /** @var \App\Core\BitrixEvent\Entity\EventMessage $eventMessage - Почтовое событие */
        $eventMessage = EventMessage::getEventMessagesByCode('ORDER_LIST_UPDATE_MANAGER', 'ru')->first();

        /** @var ProductOrderInterface $mode Тип обработчика письма заказа */
        $mode = $bitrixOrder->getMode();

        $fields = [
            'ORDER_ID'    => $bitrixOrder->getId(),
            'UPDATE_TEXT' => $update,
            'MODE'        => get_class($mode),
        ];

        foreach ($mode->getEmailTo() as $email) {
            $fields['EMAIL_TO'] = $email;

            CEvent::SendImmediate(
                $eventMessage->getEventName(),
                SiteHelper::getSiteIdByCurrentLanguage(),
                $fields,
                'Y',
                $eventMessage->getMessageId(),
                [],
                'ru'
            );
        }
    }

    /**
     * Обработчик обновления заказа
     *
     * @param int $orderId - Идентификатор заказа
     *
     * @return void
     */
    public static function checkOrder(int $orderId): void
    {
        /** @var BitrixOrder $order - Заказ */
        $order = BitrixOrder::getById($orderId);

        /** @var DateTime $thirtySecsAgo Битриксовый объект, описывающий время 30 секунд назад */
        $thirtySecsAgo = (new DateTime())->add('- 30 seconds');

        if ($order->getDateStatus() > $thirtySecsAgo && $order->getDate() < $thirtySecsAgo) {
            Email::sendMail(
                'ORDER_STATUS_UPDATE',
                [
                    'ORDER_ID'             => $order->getId(),
                    'ORDER_ACCOUNT_NUMBER' => $order->getAccountNumber(),
                    'EMAIL_TO'             => $order->user->getEmail(),
                ],
                'N',
                $order->user
            );
        }
    }

    /**
     * При изменении статуса заказа
     *
     * @param \Bitrix\Main\Event $params
     *
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public static function OnSaleStatusOrderChange(Event $params)
    {
        /** @var \Bitrix\Sale\Order $order */
        $order = $params->getParameter("ENTITY");

        if ($order->getId() <= 0) {
            return;
        }

        if ($order->getField('STATUS_ID') == 'F') {

            /** @var BitrixOrder $orderModel Модель заказа */
            $orderModel = BitrixOrder::getById($order->getId());

            if ($orderModel->getSumPaid() > 0) {
                //Отправляем чек на полный расчет
                $cheque = new FullPaymentCheque($order);
                $cheque->send();
            }
        }

    }

    /**
     * @param int $id
     */
    public static function onAdminSaleOrder($id)
    {
        /** @var Order $order */

        if (
            $id <= 0
            || !(Loader::includeModule('sale') && Loader::includeModule('iblock'))
            || ($order = Order::load($id)) === null
        ) {
            return;
        }

        $productsData = [];

        /** @var BasketItem $basketItem */
        foreach ($order->getBasket()->getBasketItems() as $basketItem) {
            $productsData[$basketItem->getProductId()] = ['NAME' =>  $basketItem->getFieldValues()['NAME']];
        }

        if (sizeof($productsData) <= 0) {
            return;
        }


        $products=JewelrySku::filter(['ID'=> array_keys($productsData)])->getList();


        foreach ($products as $product) {
            $imageFileArray = \CFile::MakeFileArray(reset($product->getPhotoSmall()));
            $imageId=\CFile::SaveFile($imageFileArray,'iblock');

            $image= \CFile::ResizeImageGet(
                $imageId,
                ['width' => 79, 'height' => 79],
                BX_RESIZE_IMAGE_PROPORTIONAL,
                false,
                false,
                false,
                false
            );

            $productsData[$product->getId()]= [
                'IMAGE' => $image['src'],
                'NAME'  => $productsData[$product->getId()]['NAME'] . ' ' . $product->getCode() . ' ' . $product->getExternalId()
                ];
        }

        Asset::getInstance()->addString(
"<script>
    BX.ready(function() {
        BX.Sale.Admin.OrderEditPage.registerFieldsUpdaters({
            'BASKET': function(basket) {

                if (!!basket.ITEMS) {

                    var customDataProducts = " . Json::encode($productsData) . " || {};

                    if (customDataProducts) {

                        for (var i in basket.ITEMS) {
                            if (basket.ITEMS.hasOwnProperty(i)) {
                                if (
                                        !!customDataProducts[basket.ITEMS[i].OFFER_ID]
                                ) {
                                    basket.ITEMS[i].PICTURE_URL = customDataProducts[basket.ITEMS[i].OFFER_ID].IMAGE;
                                    basket.ITEMS[i].NAME = customDataProducts[basket.ITEMS[i].OFFER_ID].NAME;
                                }
                            }
                        }

                    }

                }

            },
        });

    });
</script>",
            AssetLocation::AFTER_JS
        );
    }
}
