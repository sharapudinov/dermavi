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

        $order->getUserId();
        if ($order->items->filter->jewelry->isNotEmpty()) {
            $fields['EMAIL_TO'] = get_sprint_option('KRISTAL_EMAIL');
        }

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
        $eventMessageUserCode = 'NEW_ORDER_CREATE_USER';

        $userLanguageInfo = $order->user->country->getCountryLanguageInfo();
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
     * При создании заказа в каталоге запрещается продажа бриллиантов этого заказа
     *
     * @param int $orderId - Идентификатор заказа
     *
     * @return void
     */
    public static function stopOrderDiamondsSelling(int $orderId): void
    {
        try {
            /** @var BitrixOrder $order - Заказ */
            /** @var OrderViewModel $order - Объект, описывающий полную информацию о заказе */
            $order = OrderViewModel::fromOrderIds([$orderId])->first();

            $flushDiamonds    = false;
            $flushJewelry     = false;
            $flushConstructor = false;

            $stopSellingFields = [
                'PROPERTY_SELLING_AVAILABLE_VALUE'   => null,
                'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => null,
            ];

            $diamondsToStopSell = [];
            foreach ($order->getItems() as $item) {
                $product = $item->getProduct();

                if ($product instanceof JewelryConstructorReadyProduct) {
                    foreach ($product->diamonds as $diamond) {
                        $diamond->update($stopSellingFields);
                    }

                    /** @var MemcacheInitializer $memcache */
                    $memcache                                               = MemcacheInitializer::getInstance();
                    $cachedCombinations                                     = json_decode(
                        $memcache->get(JewelryHelper::JEWELRY_CONSTRUCTOR_READY_PRODUCT_COMBINATIONS_CACHE_KEY),
                        true
                    );
                    $cachedCombinations[$product->blankSku->blank->getId()] = $product->diamonds->pluck('ID')->toArray(
                    );
                    $memcache->set(
                        JewelryHelper::JEWELRY_CONSTRUCTOR_READY_PRODUCT_COMBINATIONS_CACHE_KEY,
                        json_encode($cachedCombinations)
                    );
                } else {
                    $product->update($stopSellingFields);
                }

                if ($product instanceof Diamond) {
                    $diamondsToStopSell[] = $product->getID();
                }
                if ($product instanceof JewelryConstructorReadyProduct) {
                    $diamondsToStopSell = array_merge($diamondsToStopSell, $product->diamonds->pluck('ID')->toArray());
                }

                if ($product instanceof Diamond) {
                    Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR . '_' . $product->getCode());
                    ElasticService::getInstance()->updateDiamondData($product);
                    $flushDiamonds    = true;
                    $flushConstructor = true;

                    $jewelryReadyProducts = JewelryConstructorReadyProduct::filter(['PROPERTY_'.JewelryConstructorReadyProduct::DIAMONDS_ID_PROP_CODE => $product->getId()])->getList();
                    foreach ($jewelryReadyProducts as $jReadyProduct) {
                        if ($jReadyProduct->blankSku) {
                            $cacheDirJwConsReProduct = sprintf(
                                'constructor_ready_product_sku_id_%s_diamonds_id_%s',
                                $jReadyProduct->blankSku->getId(),
                                $jReadyProduct->diamonds->pluck('ID')->implode('-')
                            );
                            Cache::flush($cacheDirJwConsReProduct);
                        }
                    }
                } elseif ($product instanceof JewelrySku) {
                    Cache::flush('jewelry_product_detail_' . $product->jewelry->getCode());
                    $flushJewelry = true;
                } else {
                    if($product->blankSku && $product->diamonds) {
                        Cache::flush(
                            'constructor_ready_product_sku_id_' . $product->blankSku->getId()
                            . '_diamonds_id_' . $product->diamonds->pluck('ID')->implode('-')
                        );

                        foreach ($product->diamonds->pluck('CODE')->toArray() as $diamondCode) {
                            Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR . '_' . $diamondCode);
                        }
                    }

                    $flushConstructor = true;
                    $flushDiamonds = true;
                }
            }

            if ($diamondsToStopSell) {
                /**
                 * @var Collection|JewelryBlankDiamonds[] $combinationsToStopSell
                 *
                 * Коллекция комбинаций для конструктора, которые надо запретить для покупки из-за того, что куплены бриллианты
                 */
                $combinationsToStopSell = JewelryBlankDiamonds::filter(['UF_DIAMONDS_ID' => $diamondsToStopSell])
                                                              ->getList();
                foreach ($combinationsToStopSell as $combination) {
                    $available = true;
                    foreach ($combination->diamonds->pluck('ID') as $diamondId) {
                        if (in_array($diamondId, $diamondsToStopSell)) {
                            $available = false;
                            break;
                        }
                    }
                    $combination->update(['UF_SELLING_AVAILABLE' => $available]);
                }
            }

            if ($flushDiamonds) {
                Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR);
            }
            if ($flushJewelry) {
                Cache::flush('jewelry_product_list');
            }
            if ($flushConstructor) {
                Cache::flush(JewelryConstructorHelper::FRAMES_LIST_CACHE_DIR);
                Cache::flush(JewelryConstructorHelper::FRAME_DETAIL_CACHE_DIR);
                Cache::flush(JewelryConstructorHelper::FRAME_DETAIL_DIAMONDS_LIST_CACHE_DIR);
                Cache::flush(JewelryConstructorHelper::DIAMONDS_LIST);
                Cache::flush(JewelryConstructorHelper::DIAMONDS_COMPLECTS_LIST);
            }
        } catch (Exception $exception) {
            logger('handlers')->error(self::class . ': ' . $exception->getMessage());
        }
    }

    /**
     * Разрешает продажу товара при отмене заказа или установке в статус, который не бронирует товар
     *
     * @param int $orderId - Номер заказа
     *
     * @return void
     */
    public static function startOrderDiamondsSelling(int $orderId): void
    {
        /** @var Collection|BitrixOrder $order - Коллекция заказов */
        $order = BitrixOrder::filter(['ID' => $orderId])->first();
        if ($order && $order->isCanceled()) {
            /** @var OrderViewModel $orderViewModel - Объект, описывающий полную информацию о заказе */
            $orderViewModel = OrderViewModel::fromOrder($order);

            /** @var \App\Core\BitrixProperty\Entity\Property $propertyDiamond - Сущность, описывающая битриксовое св-во */
            $propertyDiamond = Property::getListPropertyValue(
                Diamond::iblockID(),
                'SELLING_AVAILABLE',
                'Y'
            );
            /** @var \App\Core\BitrixProperty\Entity\Property $propertyJewelry - Сущность, описывающая битриксовое св-во */
            $propertyJewelry = Property::getListPropertyValue(
                JewelrySku::iblockID(),
                'SELLING_AVAILABLE',
                'Y'
            );

            /**
             * @var Collection|Diamond[] $sellingProducts
             * Коллекция товаров конкретного заказа, которые активировали для продажи
             */
            $sellingProducts = new Collection;
            $flushDiamonds   = false;
            $flushJewelry    = false;
            try {
                foreach ($orderViewModel->getItems() as $item) {
                    /** @var ProductInterface $product - Бриллиант из заказа */
                    $product = $item->getProduct();

                    if ($item->isDiamond() || $item->isConstructorReadyProduct()) {
                        $property = $propertyDiamond;

                        if ($item->isConstructorReadyProduct()) {
                            Cache::flush(
                                'constructor_ready_product_sku_id_' . $product->blankSku->getId()
                                . '_diamonds_id_' . $product->diamonds->pluck('ID')->implode('-')
                            );
                            Cache::flush(JewelryConstructorHelper::DIAMONDS_LIST);
                            Cache::flush(JewelryConstructorHelper::DIAMONDS_COMPLECTS_LIST);
                            Cache::flush('frame_' . $product->blankSku->blank->getId() . 'diamonds');
                        } else {
                            $flushDiamonds = true;
                            Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR . '_' . $product->getCode());
                        }
                    }
                    if ($item->isJewelry()) {
                        $property     = $propertyJewelry;
                        $flushJewelry = true;
                        Cache::flush('jewelry_product_detail_' . $product->jewelry->getCode());
                    }

                    $startSellingFields = [
                        'PROPERTY_SELLING_AVAILABLE_VALUE'   => 'Y',
                        'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => $property->getVariantId(),
                    ];
                    $sellingProducts->push($product);

                    if ($product instanceof JewelryConstructorReadyProduct) {
                        foreach ($product->diamonds as $diamond) {
                            $diamond->update($startSellingFields);
                            Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR . '_' . $diamond->getCode());
                        }
                    } else {
                        $product->update($startSellingFields);
                    }

                    if ($item->isDiamond()) {
                        $flushDiamonds = true;
                        Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR . '_' . $product->getCode());
                        ElasticService::getInstance()->updateDiamondData($product);

                        $jewelryReadyProducts = JewelryConstructorReadyProduct::filter(['PROPERTY_'.JewelryConstructorReadyProduct::DIAMONDS_ID_PROP_CODE => $product->getId()])->getList();
                        foreach ($jewelryReadyProducts as $jReadyProduct) {
                            if ($jReadyProduct->blankSku) {
                                $cacheDirJwConsReProduct = sprintf(
                                    'constructor_ready_product_sku_id_%s_diamonds_id_%s',
                                    $jReadyProduct->blankSku->getId(),
                                    $jReadyProduct->diamonds->pluck('ID')->implode('-')
                                );
                                Cache::flush($cacheDirJwConsReProduct);
                            }
                        }
                    }
                }

                Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR);
            } catch (Throwable $exception) {
                logger('catalog')->error(
                    self::class . ': Не удалось восстановить товары заказа '
                    . $order->getId() . ' для продажи. Причина: '
                    . $exception->getMessage()
                );

                foreach ($sellingProducts as $sellingProduct) {
                    $sellingProduct->update(
                        [
                            'PROPERTY_SELLING_AVAILABLE_VALUE'   => null,
                            'PROPERTY_SELLING_AVAILABLE_ENUM_ID' => null,
                        ]
                    );
                }
            }

            if ($flushDiamonds) {
                Cache::flush(Diamond::CATALOG_CACHE_INIT_DIR);
            }
            if ($flushJewelry) {
                Cache::flush('jewelry_product_list');
            }
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
