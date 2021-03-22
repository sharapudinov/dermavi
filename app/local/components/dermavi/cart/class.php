<?php

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Core\Currency\Currency;
use App\Core\Currency\Entity\CurrencyEntity;
use App\Core\Sale\Entity\CartType\DefaultCartType;
use App\Core\Sale\View\OrderItemViewModel;
use App\EventHandlers\CartHandlers;
use App\Helpers\LanguageHelper;
use App\Helpers\TTL;
use App\Helpers\UserCartHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\Jewelry\JewelrySku;
use App\Seo\GlobalSiteTag\GlobalSiteTagCartEventAdder;
use App\Seo\GlobalSiteTag\GlobalSiteTagEvent;
use App\Seo\GlobalSiteTag\GlobalSiteTagHandler;
use Bitrix\Main\ArgumentNullException;
use Illuminate\Support\Collection;
use Throwable;

use function logException;

/**
 * Класс-контроллер для работы с корзиной и оформлением заказа
 * Class SaleCart
 * @package App\Local\Component
 */
class Cart extends BaseComponent
{
    /** @var string - Название шаблона для пустой корзины */
    private const EMPTY_TEMPLATE = 'empty';

    /** @var string $template - Шаблон компонента */
    private $template;

    /**
     * Запускаем компонент
     *
     * @return void
     * @throws ArgumentNullException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    public function executeComponent(): void
    {
        $this->loadData();
        $this->chooseTemplate();

        $this->includeComponentTemplate($this->template);
    }

    /**
     * Загружаем данные по корзине пользователя
     *
     * @return void
     * @throws ArgumentNullException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    private function loadData(): void
    {
        $user = user();
        /** Если пользователь авторизован, то кешируем для него корзину */
        if ($user) {
            $data = $this->loadCartForAuthorizedUser();
        } else {
            $data = $this->loadCart();
        }

        $cartCost = $data['cart_cost'] + $data['cart_service_cost'];

        $items = $data['basket_items']->map(
            function ($item) {
                $result = $item->fields;
                $result['properties'] = $item->properties()->getList()->map(
                    function ($item) {
                        return $item->fields;
                    }
                )->values()->all();
                return $result;
            }
        )->values()->all();

        $this->arResult['summary'] = [
            'currency'         => $data['currency'],
            'cartCost'         => $cartCost,
            'cartItemCost'     => $data['cart_item_cost'],
            'deliveryCost'     => $data['delivery_cost'],
            'cartItemDiscount' => $data['cart_item_discount'],
            'canCheckout'      => UserCartHelper::canCheckout($user, $data['cart']),
            'canPay'           => UserCartHelper::canPay(),
            'isLegalEntity'    => $user && $user->isLegalEntity(),
            'isAuthorized'     => $user && $user->isAuthorized(),
            'user'             => user(),
            'floatCartPrice'   => $data['float_cart_price'],
            'defaultCurrency'  => (new Currency())->getDefaultCurrency()
        ];
        $this->arResult['items'] = $items;
    }

    /**
     * Загружаем закешированные данные для авторизованного пользователя
     *
     * @return array
     * @throws ArgumentNullException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    private function loadCartForAuthorizedUser(): array
    {
        /** @var string $cacheKey - Ключ для кеширования корзины */
        // Отключил кеширование. Зачем оно здесь?
        $useCache = false;
        if ($useCache) {
            $cacheKey = CartHandlers::getCacheInitDir()
                . LanguageHelper::getLanguageVersion()
                . Currency::getCurrentCurrency()->getSymCode();

            return cache(
                $cacheKey,
                TTL::DAY,
                function () {
                    return $this->loadCart();
                },
                CartHandlers::getCacheInitDir()
            );
        }

        return $this->loadCart();
    }

    /**
     * Загружаем данные по корзине пользователя
     *
     * @return array
     * @throws ArgumentNullException
     * @throws \Arrilot\BitrixModels\Exceptions\ExceptionFromBitrix
     */
    private function loadCart(): array
    {
        return UserCartHelper::loadCartInfo(new DefaultCartType());
    }

    /**
     * Выбираем шаблон для корзины
     *
     * @return void
     */
    private function chooseTemplate(): void
    {
        if (is_ajax()) {
            if (check_csrf_token()) {
                $this->template = e($this->request->getPost('template'));
            }
        } else {
            if (!count($this->arResult['items'])) {
                $this->template = self::EMPTY_TEMPLATE;
            }
        }
    }

    /**
     * Добавляет событие gtag
     */
    private function gtagAddEvent(): void
    {
        /** @var float|null $cartCost */
        $cartCost = $this->arResult['cartCost'] ?? null;

        /** @var CurrencyEntity|null $currency */
        $currency = $this->arResult['currency'] ?? null;

        if (!$cartCost || !$currency) {
            return;
        }

        /** @var Collection|OrderItemViewModel[]|null $basketItems */
        $basketItems = $this->arResult['basketItems'] ?? null;

        if (!$basketItems || $basketItems->isEmpty()) {
            return;
        }

        (new GlobalSiteTagCartEventAdder(
            'add_to_cart',
            (int)ceil($cartCost),
            $currency->getSymCode(),
            $basketItems
        ))->run();
    }
}
