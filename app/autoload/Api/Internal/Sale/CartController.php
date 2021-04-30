<?php

namespace App\Api\Internal\Sale;

use App\Api\BaseController;
use App\Core\Sale\Entity\CartType\CartTypeFactory;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\UserCart;
use App\Core\Sale\View\OrderItemViewModel;
use App\EventHandlers\CartHandlers;
use App\Helpers\TTL;
use App\Helpers\UserCartHelper;
use App\Models\Auxiliary\Sale\BitrixBasketItem;
use App\Models\Catalog\Catalog;
use App\Models\Catalog\HL\PaidServiceCategory;
use App\Models\Catalog\PaidService;
use App\Models\Catalog\PaidServices\EngravingService;
use App\Models\Catalog\ProductFactory;
use App\Models\Catalog\ProductInterface;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use Bitrix\Main\Loader;
use Bitrix\Sale\BasketItem;
use Exception;
use Interop\Container\ContainerInterface;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Класс-контроллер для работы с корзиной
 * Class CartController
 * @package App\Api\Internal\Sale
 */
class CartController extends BaseController
{
    /**
     * OrderController constructor.
     * @param ContainerInterface $container
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        Loader::includeModule('sale');
    }

    /**
     * Добавляем товары в корзину
     *
     * @return ResponseInterface
     */
    public function setToCart(): ResponseInterface
    {
//        try {
        $basketItemId = null;
        $cartType = CartTypeFactory::getCartType($this->getParam('type'));

        /** @var \Bitrix\Sale\Basket $cart - Корзина пользователя */
        $cart = UserCart::getUserCart(new DefaultCartType());

        $cartProductIds[] = array_map(
            function ($item) {
                /** @var BasketItem $item */
                return $item->getField('PRODUCT_ID');
            },
            $cart->getBasketItems()
        );
        $properties=$this->getParam('properties');
        if ($productId = e($this->getParam('productId'))) {
            $basketItemId = UserCart::addProduct(
                $productId,
                $this->getParam('quantity'),
                $properties,
                $cartType
            );
            $cartProductIds[] = $productId;
            $productIds[] = $productId;
        }
        CartHandlers::flushCartCache();
        return $this->respondWithSuccess(
            [
                'count'        => UserCartHelper::getCartPositionsCount(),
                'basketItemId' => $basketItemId,
                'addedItems'   => $productIds,
            ]
        );
    }

    /**
     * Удаляем товары из корзины
     * Если не передать productId, то будет очищена вся корзина. Смотри метод UserCart::removeFromCart
     *
     * @return ResponseInterface
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function removeFromCart(): ResponseInterface
    {
        /** @var int $productId - Идентификатор удаляемого из корзины товара */
        $productId = (int)e($this->getParam('productId'));

        $cartType = CartTypeFactory::getCartType($this->getParam('type'));
        CartHandlers::flushCartCache();
        $cart = UserCart::getInstance($cartType);

        UserCart::removeFromCart($productId);
        $cart->refreshBasketInfo();
        return $this->respondWithSuccess($cart->getBasketItems()->count());
    }
}
