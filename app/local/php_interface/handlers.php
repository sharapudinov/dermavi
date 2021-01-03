<?php

/**
 * В данном файле регистрируются все обработчики Битриксовых событий.
 *
 * Исключение:
 * Если событие касается жизненного цикла запроса (OnPageStart, OnBeforeProlog, OnProlog, OnEpilog, OnAfterEpilog), то
 * его обработчики должны быть зарегистрированы не здесь, а в request_hooks.php
 *
 * Каждый обработчик должен обязательно быть методом класса лежащего в app/autoload/EventHandlers/
 * Там уже создан класс UserHandlers. Дальше лучше всего создавать для каждый сущности отдельный класс:
 * ProductHandlers, FeedbackHandlers и т.д
 *
 * В названии метода-обработчика должно четко отражаться событие:
 * 1. Действие которое в нём выполняется
 * 2. Событие(я) для которых он вызывается
 *
 * Примеры
 * NewsHandlers::generateUniqueCodeBeforeAdd - хорошее название
 * NewsHandlers::onBeforeIBlockElementAddHandler - плохое
 */

use App\Core\Auxiliary\BitrixMenu\AdminMenu;
use App\Core\Auxiliary\IblockTabs\GeneralTab;
use App\Core\Auxiliary\PropertyType\IblockPropertyAuctionBetsTrack;
use App\Core\Auxiliary\PropertyType\IblockPropertyAuctionLogs;
use App\Core\Auxiliary\PropertyType\IblockPropertyAuctionWinners;
use App\Core\Auxiliary\PropertyType\IblockPropertyHtml;
use App\Core\Auxiliary\PropertyType\IblockPropertyLink;
use App\Core\Auxiliary\UserType\HtmlEmailShowType;
use App\Core\Auxiliary\UserType\UserLinkUserType;
use App\Core\Auxiliary\UserType\UtmLogsUserType;
use App\Core\Sale\Payture\PaytureBitrixCashbox;
use App\EventHandlers\AdminMenuHandlers;
use App\Core\Delivery\CccbDeliveryService;
use App\EventHandlers\AuctionHandlers;
use App\EventHandlers\AuctionLotHandlers;
use App\EventHandlers\AuctionPBHandlers;
use App\EventHandlers\AuctionPBLotHandlers;
use App\EventHandlers\CacheHandlers;
use App\EventHandlers\LoggingEmailHandler;
use App\EventHandlers\MainHandlers;
use App\EventHandlers\OrderHandlers;
use App\EventHandlers\ConstructorOrderHandler;
use App\EventHandlers\CallbackFormHandlers;
use App\EventHandlers\CartHandlers;
use App\EventHandlers\SearchHandlers;
use App\EventHandlers\UserHandlers;
use App\EventHandlers\UtmAuctionLogHandlers;

$em = Bitrix\Main\EventManager::getInstance();
$em->addEventHandler('main', 'OnPanelCreate', [MainHandlers::class, "addFrontendButtonToTopPanel"]);

///** Кастомные типы свойств инфоблоков */
//$em->addEventHandler(
//    'iblock',
//    'OnIBlockPropertyBuildList',
//    [IblockPropertyLink::class, 'GetUserTypeDescription']
//);
//
//$em->addEventHandler(
//    'iblock',
//    'OnIBlockPropertyBuildList',
//    [IblockPropertyAuctionBetsTrack::class, 'GetUserTypeDescription']
//);
//
//$em->addEventHandler(
//    'iblock',
//    'OnIBlockPropertyBuildList',
//    [IblockPropertyAuctionWinners::class, 'GetUserTypeDescription']
//);
//
//$em->addEventHandler(
//    'main',
//    'OnUserTypeBuildList',
//    [IblockPropertyHtml::class, 'GetUserTypeDescription']
//);
//
//$em->addEventHandler(
//    'iblock',
//    'OnIBlockPropertyBuildList',
//    [IblockPropertyAuctionLogs::class, 'GetUserTypeDescription']
//);
//
///** Дополнительные пользовательские типы */
//$em->addEventHandler('main', 'OnUserTypeBuildList', [UserLinkUserType::class, 'GetUserTypeDescription']);
//
///** Пользовательское св-во логи utm аукционов */
//$em->addEventHandler('main', 'OnUserTypeBuildList', [UtmLogsUserType::class, 'GetUserTypeDescription']);
//// события отлова utm логов аукциона
//$em->addEventHandler('main', 'OnBeforeProlog', [UtmAuctionLogHandlers::class, 'saveLogs']);
//
///** Обработка кастомных табов */
//$em->addEventHandler('main', 'OnAdminIBlockElementEdit', [GeneralTab::class, 'initTab']);
//
///** Обработка кастомных пунктов меню в админке */
//$em->addEventHandler('main', 'OnBuildGlobalMenu', [AdminMenu::class, "addApplicationMenu"]);
//
///** Форма обратной связи */
//$em->addEventHandler('', 'GetInTouchFormOnAfterAdd', [CallbackFormHandlers::class, 'sendEmailToManager']);
//
///** Корзина */
//$em->addEventHandler('sale', 'OnBasketAdd', [CartHandlers::class, 'flushCartCache']);
//$em->addEventHandler('sale', 'OnBasketUpdate', [CartHandlers::class, 'flushCartCache']);
//$em->addEventHandler('sale', 'OnBasketDelete', [CartHandlers::class, 'flushCartCache']);
//
///** Заказ */
//$em->addEventHandler('sale', 'OnOrderUpdate', [OrderHandlers::class, 'checkOrder']);
//$em->addEventHandler('sale', 'OnSaleCancelOrder', [OrderHandlers::class, 'startOrderDiamondsSelling']);
//$em->addEventHandler('sale', 'OnSaleStatusOrderChange', [OrderHandlers::class, 'OnSaleStatusOrderChange']);
////админка форма обработки заказа
//$em->addEventHandler('main','OnAdminSaleOrderView',[OrderHandlers::class, 'onAdminSaleOrder']);
//$em->addEventHandler('main','OnAdminSaleOrderEdit',[OrderHandlers::class, 'onAdminSaleOrder']);
////админка форма обработки заказа (кастомные блоки для конструктора)
//$em->addEventHandler('main','OnAdminSaleOrderViewDraggable',[ConstructorOrderHandler::class, 'onInit']);
//
//// Обработчик службы доставки спецсвязью
//$em->addEventHandler('sale', 'onSaleDeliveryHandlersClassNamesBuildList', [CccbDeliveryService::class, "register"]);
//
///** Аукционы */
//// Создание аукциона
//$em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [AuctionHandlers::class, 'createAuction']);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionHandlers::class, 'controlAuctionLots'], false, 2);
//
//// Изменение аукциона
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionHandlers::class, 'rememberAuctionFields'], false, 100);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [AuctionHandlers::class, 'updateAuctionFields'], false, 101);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionHandlers::class, 'controlAuctionLots'], false, 2);
//
//// Создание лота аукциона
//$em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [AuctionLotHandlers::class, 'createAuctionLot']);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionLotHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionLotHandlers::class, 'controlAuctionLotType'], false, 2);
//
//// Изменение лота аукциона
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionLotHandlers::class, 'rememberLotFields'], false, 105);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [AuctionLotHandlers::class, 'updateLotFields'], false, 106);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionLotHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionLotHandlers::class, 'controlAuctionLotType'], false, 2);
//
///** Аукционы PB */
//// Создание аукциона
//$em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [AuctionPBHandlers::class, 'createAuction']);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionPBHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionPBHandlers::class, 'controlAuctionLots'], false, 2);
//
//// Изменение аукциона
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBHandlers::class, 'rememberAuctionFields'], false, 100);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [AuctionPBHandlers::class, 'updateAuctionFields'], false, 101);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBHandlers::class, 'controlAuctionLots'], false, 2);
//
//// Создание лота аукциона
//$em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [AuctionPBLotHandlers::class, 'createAuctionLot']);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionPBLotHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [AuctionPBLotHandlers::class, 'controlAuctionLotType'], false, 2);
//
//// Изменение лота аукциона
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBLotHandlers::class, 'rememberLotFields'], false, 105);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [AuctionPBLotHandlers::class, 'updateLotFields'], false, 106);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBLotHandlers::class, 'checkCode'], false, 1);
//$em->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [AuctionPBLotHandlers::class, 'controlAuctionLotType'], false, 2);
//
////Обновление поискового индекса
//$em->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', [SearchHandlers::class, 'updateElasticIndex']);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [SearchHandlers::class, 'updateElasticIndex']);
//$em->addEventHandler('iblock', 'OnAfterIBlockElementDelete', [SearchHandlers::class, 'deleteElasticIndex']);
//
///** Обход родного обработчик главного модуля битрикс `OnChangeFile` */
//$em->addEventHandler('main', 'OnChangeFile', [MainHandlers::class, 'OnChangeFileComponent']);
//
//$em->addEventHandler('main', 'OnAdminContextMenuShow', [AdminMenuHandlers::class, 'OrderDetailAdminContextMenuShow']);
//
//
//// =====================================================================================================================
//// ============================= Обработчики действий с пользователем ==================================================
//// =====================================================================================================================
//
//// Проверка обязательности поля UF_COMPANY_ID есть пользователь - юр. лицо
//$em->addEventHandler('main', 'OnBeforeUserUpdate', [UserHandlers::class, "checkRequiredFields"]);
//$em->addEventHandler('main', 'OnAfterUserLogin', [UserHandlers::class, "updateClientPB"]);
//$em->addEventHandler('sale', 'OnGetCustomCashboxHandlers', [PaytureBitrixCashbox::class, "register"]);
//
//// Логирование отправки писема
//$em->addEventHandler('main', 'OnBeforeMailSend', [LoggingEmailHandler::class, "loggingMail"]);
//$em->addEventHandler('main', 'OnUserTypeBuildList', [HtmlEmailShowType::class, 'GetUserTypeDescription']);

/** Сброс кэша при изменении HL-блоков */
CacheHandlers::register($em);
