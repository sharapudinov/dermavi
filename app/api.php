<?php

/**
 * В этом файле регистрируются роуты для апи и аякс запрсов.
 * Подробнее о роутинге читать тут - https://www.slimframework.com/docs/objects/router.html
 */

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => !in_production(),
    ],
]);

// Группа роутов для аякс запросов из Битрикса.
$app->group('/internal', function () {
    $this->any('/component/{name}[/{template}]', 'App\Api\Internal\ComponentController:show');

    //Обратная связь
    $this->post('/main/callback/', 'App\bitrixr:addRequest');

    //Форма обратной связи из хедера
    $this->post('/main/callbackform/', 'App\Api\Internal\Main\FormCallBackController:addRequest');

    //Веб-формы
    $this->group('/web-form', function () {
        $this->post('/send/{webFormId}', 'App\Api\Internal\Main\WebFormController:send');
    });

    //Валюта
    $this->post('/main/currency/change/{currency}', 'App\Api\Internal\Main\CurrencyController:changeCurrency');
    //Пользователь
    $this->post(
        '/user/subscription/add/news/',
        'App\Api\Internal\User\SubscriptionController:addUserToMarketingSubscription'
    );
    $this->get('/user/auth/signup/', 'App\Api\Internal\User\AuthController:signUp');
    $this->get('/user/auth/signin/', 'App\Api\Internal\User\AuthController:signIn');
    $this->get('/user/auth/send-restore-link/', 'App\Api\Internal\User\AuthController:sendRestoreLink');
    $this->get('/user/auth/set-new-password/', 'App\Api\Internal\User\AuthController:setNewPassword');
    $this->post('/user/auth/change-password/', 'App\Api\Internal\User\AuthController:changePassword');
    $this->post('/user/auth/logout/', 'App\Api\Internal\User\AuthController:logout');
    $this->get('/user/profile/update-profile/', 'App\Api\Internal\User\ProfileController:updateProfile');
    $this->get('/user/profile/delivery-address/add/', 'App\Api\Internal\User\AddressController:addNewAddress');
    $this->get('/user/profile/delivery-address/update/', 'App\Api\Internal\User\AddressController:updateAddress');
    $this->post(
        '/user/profile/delivery-address/delete/{addressId}',
        'App\Api\Internal\User\AddressController:removeAddress'
    );
    $this->post(
        '/user/profile/documents-personal-info/add/',
        'App\Api\Internal\User\ProfileController:addUserInfoInDocs'
    );
    $this->post('/user/profile/diamond-order/add/', 'App\Api\Internal\User\ProfileController:addDiamondOrder');

    //список
    $this->get('/header-fields/', 'App\Api\Internal\HeaderFields\HeaderFieldsController:getHeaderFields');
    $this->post('/header-fields/', 'App\Api\Internal\HeaderFields\HeaderFieldsController:setHeaderFields');

    //Поиск
    $this->group('/search', function () {
        $this->get('/suggest', '\App\Api\Internal\Search\SearchController:suggest');
    });

    //Документы
    $this->post('/user/profile/documents/add/', 'App\Api\Internal\User\DocumentController:addDocument');
    $this->post('/user/profile/documents/update/', 'App\Api\Internal\User\DocumentController:updateDocument');

    //Личная анкета
    $this->post('/user/profile/form', 'App\Api\Internal\User\FormController:setInfo');

    //Подписки
    $this->post('/user/subscription/add/', 'App\Api\Internal\User\SubscriptionController:add');
    $this->put('/user/subscription/edit/', 'App\Api\Internal\User\SubscriptionController:edit');

    $this->post('/user/profile/send-to-crm', 'App\Api\Internal\User\ProfileController:sendProfileToReview');

    //Бриллиант
    $this->post('/main/sale/diamond/search/{packetId}', 'App\Api\Internal\Sale\DiamondController:searchDiamond');
    $this->post('/main/share/share-this-diamond/', 'App\Api\Internal\Main\ShareController:shareDiamond');
    $this->post('/main/share/share-this-jewelry/', 'App\Api\Internal\Main\ShareController:shareJewelry');
    $this->post('/main/share/share-this-constructed-jewelry/', 'App\Api\Internal\Main\ShareController:shareConstructedJewelry');
    $this->post('/main/share/share-this-frame/', 'App\Api\Internal\Main\ShareController:shareFrame');
    $this->get('/main/viewing/request/', 'App\Api\Internal\Main\ViewingController:requestViewing');
    $this->post('/sale/filter/from-diamond-detail/', 'App\Api\Internal\Sale\FilterController:searchFromDiamondDetail');

    //Корзина
    $this->post('/sale/cart/add/', 'App\Api\Internal\Sale\CartController:setToCart');
    $this->post('/sale/cart/remove/', 'App\Api\Internal\Sale\CartController:removeFromCart');
    $this->post(
        '/sale/cart/add-diamond-message/{orderItemId}/',
        'App\Api\Internal\Sale\CartController:addDiamondMessage'
    );
    $this->post(
        '/sale/cart/remove-diamond-message/{cartItemId}/',
        'App\Api\Internal\Sale\CartController:removeDiamondMessage'
    );
    $this->post(
        '/sale/cart/add-engraving/{orderItemId}/{diamondId}/',
        'App\Api\Internal\Sale\CartController:addEngraving'
    );
    $this->post(
        '/sale/cart/add-gia-certificate/{orderItemId}/{diamondId}/',
        'App\Api\Internal\Sale\CartController:addGiaCertificate'
    );
    $this->post(
        '/sale/cart/remove-paid-service/{orderItemId}/',
        'App\Api\Internal\Sale\CartController:removePaidService'
    );

    //Чекаут
    $this->get('/delivery-service/calculated-price/', 'App\Api\Internal\Sale\DeliveryController:getCalculatedPrice');
    $this->get('/geolocation/country/is-russia', 'App\Api\Internal\Geolocation\CountryController:isRussia');

    //Заказ
    $this->post(
        '/sale/order/{orderId}/cancel/',
        'App\Api\Internal\Sale\OrderController:cancelOrder'
    );

    $this->post(
        '/sale/order/items/{orderItemId:[\d]+}/properties/',
        'App\Api\Internal\Sale\OrderController:setOrderItemCustomProperties'
    );
    $this->post(
        '/sale/order/items/{orderItemId:[\d]+}/properties/remove/',
        'App\Api\Internal\Sale\OrderController:removeOrderItemCustomProperties'
    );
    $this->post(
        '/sale/order/items/{orderItemId:[\d]+}/engraving/',
        'App\Api\Internal\Sale\OrderController:addEngraving'
    );
    $this->post(
        '/sale/order/items/{orderItemId:[\d]+}/certificate/',
        'App\Api\Internal\Sale\OrderController:addCertificateOrder'
    );
    $this->post(
        '/sale/order/items/{orderItemId:[\d]+}/remove-paid-service/',
        'App\Api\Internal\Sale\OrderController:removePaidService'
    );

    $this->get('/sale/pickup-point/get-list', 'App\Api\Internal\Sale\PickupPointController:getList');
    $this->get('/sale/viewing-point/get-list', 'App\Api\Internal\Main\ViewingController:getOfficesToView');

    $this->post('/sale/order/create/', 'App\Api\Internal\Sale\OrderController:createOrder');

    //Трейсинг
    $this->any(
        '/tracing/diamond-story/specific/',
        'App\Api\Internal\Tracing\TracingController:exploreSpecificDiamondStory'
    );
    $this->any(
        '/tracing/diamond-story/random/',
        'App\Api\Internal\Tracing\TracingController:exploreRandomDiamondStory'
    );

    // Посмотреть справочник в КАДАС-е
    $this->get('/cadas/test/dicts/', '\App\Api\Internal\Cadas\CadasController:showDict');

    //Аукционы
    $this->post('/auctions/search/{lotId}/', '\App\Api\Internal\Auction\AuctionController:findLot');
    $this->get(
        '/auctions/search-in-auction/{auctionId}/{lotId}/',
        '\App\Api\Internal\Auction\AuctionController:findInSpecificAuction'
    );
    $this->post('/auction/accept-bet/', '\App\Api\Internal\Auction\AuctionController:acceptBet');
    $this->post('/auction/accept-multiple-bets/', '\App\Api\Internal\Auction\AuctionController:acceptMultipleBets');
    $this->post('/auction/remove-bet/', '\App\Api\Internal\Auction\AuctionController:removeBet');
    $this->get('/auction/request-notify/', '\App\Api\Internal\Auction\AuctionController:requestNotify');


    $this->post(
        '/auction/admin/attach-products-to-lot/',
        '\App\Api\Internal\Auction\AuctionAdministrationController:attachProductsToLot'
    );
    $this->post(
        '/auction/admin/attach-products-to-lot-by-csv/',
        '\App\Api\Internal\Auction\AuctionAdministrationController:attachProductsToLotByCsv'
    );
    $this->post(
        '/auction/admin/set-request-viewing-time-slots/{auctionId}',
        '\App\Api\Internal\Auction\AuctionAdministrationController:setRequestViewingTimeSlots'
    );
    $this->post(
        '/auction/admin/create-auction-by-csv/',
        '\App\Api\Internal\Auction\AuctionAdministrationController:createAuctionByCsv'
    );
    $this->post(
        '/auction/admin/attach-users-to-notificate/',
        '\App\Api\Internal\Auction\AuctionAdministrationController:attachUsersToNotificate'
    );

    //Аукционы PB
    $this->post('/auctions_pb/search/{lotId}/', '\App\Api\Internal\Auction\AuctionPBController:findLot');
    $this->get(
        '/auctions_pb/search-in-auction/{auctionId}/{lotId}/',
        '\App\Api\Internal\Auction\AuctionPBController:findInSpecificAuction'
    );
    $this->post('/auction_pb/accept-bet/', '\App\Api\Internal\Auction\AuctionPBController:acceptBet');
    $this->post('/auction_pb/accept-multiple-bets/', '\App\Api\Internal\Auction\AuctionPBController:acceptMultipleBets');
    //$this->post('/auction_pb/remove-bet/', '\App\Api\Internal\Auction\AuctionPBController:removeBet');
    $this->get('/auction_pb/request-notify/', '\App\Api\Internal\Auction\AuctionPBController:requestNotify');

    $this->post(
        '/auction_pb/admin/attach-products-to-lot/',
        '\App\Api\Internal\Auction\AuctionPBAdministrationController:attachProductsToLot'
    );
    $this->post(
        '/auction_pb/admin/attach-products-to-lot-by-csv/',
        '\App\Api\Internal\Auction\AuctionPBAdministrationController:attachProductsToLotByCsv'
    );
    $this->post(
        '/auction_pb/admin/set-request-viewing-time-slots/{auctionId}',
        '\App\Api\Internal\Auction\AuctionPBAdministrationController:setRequestViewingTimeSlots'
    );
    $this->post(
        '/auction_pb/admin/create-auction-by-csv/',
        '\App\Api\Internal\Auction\AuctionPBAdministrationController:createAuctionByCsv'
    );
    $this->post(
        '/auction_pb/admin/attach-users-to-notificate/',
        '\App\Api\Internal\Auction\AuctionPBAdministrationController:attachUsersToNotificate'
    );

    $this->get('/main/offices/get-list', 'App\Api\Internal\Main\ContactController:officesGetList');

    //Список желаний/ избранное
    $this->post('/catalog/wishlist/add/', 'App\Api\Internal\Catalog\WishlistController:add');
    $this->post('/catalog/wishlist/remove/', 'App\Api\Internal\Catalog\WishlistController:remove');
    $this->post('/catalog/wishlist/hide/', 'App\Api\Internal\Catalog\WishlistController:hide');
    $this->post('/catalog/wishlist/show/', 'App\Api\Internal\Catalog\WishlistController:show');

    //Операции, связанные с политикой конфиденциальности
    $this->post('/main/privacy-policy/accept/', '\App\Api\Internal\Main\PrivacyPolicyController:acceptPrivacyPolicy');

    //Работы с формами
    $this->get(
        '/forms/address/get-regions-for-country/{countryId}/',
        'App\Api\Internal\Forms\AddressController:getRegionsForCountry'
    );

    //PDF
    $this->post('/main/pdf/product-card-pdf/', 'App\Api\Internal\Main\PdfController:generateProductCardPdf');
    $this->post(
        '/main/pdf/alrosa-product-certificate-pdf/',
        'App\Api\Internal\Main\PdfController:generateAlrosaCertificatePdf'
    );
    $this->post('/main/pdf/personal-client-form/', 'App\Api\Internal\Main\PdfController:generatePersonalFormPdf');
    $this->post('/main/pdf/auction-info/', '\App\Api\Internal\Main\PdfController:generateAuctionInfoPdf');
    $this->post(
        '/jewelry/constructor/individual-order',
        '\App\Api\Internal\Forms\JewelryConstructorIndividualOrderController:add'
    );

    // Конструктор ЮБИ
    $this->group('/jewelry-constructor', function () {
        $this->get(
            '/frame-diamonds-combination',
            '\App\Api\Internal\Catalog\JewelryConstructorController:getAutoCombinationForFrame'
        );

        $this->post(
            '/ready-product',
            '\App\Api\Internal\Catalog\JewelryConstructorController:createReadyProductInDatabase'
        );

        $this->group('/constructing-item', function () {
            $this->get('', '\App\Api\Internal\Catalog\JewelryConstructorController:hasConstructingItem');
            $this->post('', '\App\Api\Internal\Catalog\JewelryConstructorController:rememberConstructorProductStateForUser');
            $this->delete('', '\App\Api\Internal\Catalog\JewelryConstructorController:deleteConstructingItem');
        });
    });
});//->add(new \App\Api\Internal\SiteMiddleware());

// Заготовка под внешнее API
$app->group('/v1', function () {
    $this->put('/users/crm/crm-id/', '\App\Api\V1\CRM\UserController:updateUserCrmId')
        ->add(new \App\Api\V1\RequestMiddleware());

    $this->group('/auctions', function () {
        $this->get('/log-rebidding-email-action', '\App\Api\V1\Auctions\EmailController:logRebiddingEmailAction');
    });

    $this->any('/payture/notification/refund', 'App\Api\External\Payture\NotificationController:refund');

    $this->group('/admin', function () {
        $this->post('/articles', '\App\Api\V1\ArticlesController:add');
        $this->post('/articles/update', '\App\Api\V1\ArticlesController:update');

        $this->group('/auction', function () {
            // Обработчик отправки формы со страницы админки: "Аукционы - Импорт бриллиантов по их номеру (для аукционов)"
            $this->post(
                '/get-diamonds-by-number',
                '\\App\\Api\\Internal\\Auction\\AuctionAdministrationController:getAuctionDiamondsByNumber'
            );
            // Обновление камней контрактов
            $this->get(
                '/update-diamonds/',
                '\App\Api\Internal\Auction\AuctionAdministrationController:updateDiamonds'
            );
            $this->get(
                '/show-sale-diamonds/',
                '\App\Api\Internal\Auction\AuctionAdministrationController:showSaleDiamonds'
            );

            $this->post(
                '/update-stock-diamonds',
                '\App\Api\Internal\Auction\AuctionAdministrationController:updateStockDiamonds'
            );
        });

        $this->post(
            '/diamonds/multiple-images-attach',
            '\App\Api\V1\Diamonds\DiamondsController:multipleImagesUpdate'
        );
        $this->post('/diamonds/samples-attach', '\App\Api\V1\Diamonds\DiamondsController:samplesUpdate');

        // @todo ALRSUP-738: страница убрана
        // Обработчик отправки формы со страницы админки: "Бриллианты - Импорт бриллиантов по их номеру"
        $this->post(
            '/diamonds/get-diamonds-by-number',
            '\\App\\Api\\Internal\\Cadas\\CadasController:getDiamondsByNumber'
        );

        $this->group('/jewelry', function () {
            $this->post('/types-attach', '\App\Api\V1\Jewelry\JewelryTypeController:attachJewelryToType');
            $this->post('/types-detach', '\App\Api\V1\Jewelry\JewelryTypeController:detachJewelryFromType');

            $this->post('/recommendation/set-view', '\App\Api\V1\Jewelry\JewelryController:setView');
            $this->post('/recommendation/remove-view', '\App\Api\V1\Jewelry\JewelryController:removeView');

            $this->post('/generator/run', '\App\Api\V1\Jewelry\JewelryController:runGenerator');
        });

        //Возвраты платежей
        $this->any(
            '/sale/payment/items/{orderId:[\d]+}',
            'App\Api\Internal\Sale\RefundController:getItems'
        );
        $this->any(
            '/sale/payment/refund/{orderId:[\d]+}',
            'App\Api\Internal\Sale\RefundController:refund'
        );
    });
});

return $app;
