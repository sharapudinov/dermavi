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
    $this->post('/main/callback/', 'App\Api\Internal\Main\CallbackController:addRequest');

    //Форма обратной связи из хедера
    $this->post('/main/callbackform/', 'App\Api\Internal\Main\FormCallBackController:addRequest');

    //Форма быстрый заказ
    $this->post('/main/quick-order/', 'App\Api\Internal\Main\QuickOrderFormController:addRequest');

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


    //Корзина
    $this->post('/sale/cart/add/', 'App\Api\Internal\Sale\CartController:setToCart');
    $this->post('/sale/cart/remove/', 'App\Api\Internal\Sale\CartController:removeFromCart');

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

});


return $app;
