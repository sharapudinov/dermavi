<?php

namespace App\Local\Component;

use App\Components\BaseComponent;
use App\Core\Currency\Currency;
use App\Core\Jewelry\Constructor\Config;
use App\Helpers\LanguageHelper;
use App\Helpers\UserHelper;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

/**
 * Класс-контроллер для работы с одним товаром
 * Class CatalogProductItem
 * @package App\Local\Component
 */
class CatalogProductItem extends BaseComponent
{
    /** @var \App\Models\Catalog\Catalog - Продукт */
    private $product;

    /** @var array|null $checkedProductsInfo - Массив товаров, которые были отложены для помещения в корзину */
    private $checkedProductsInfo;

    /** @var string $view - Вид списка */
    private $view;

    /** @var bool|null $isInCart - Находится ли товар в корзине */
    private $isInCart;

    /** @var bool|null $showDiamondsCount - Выводить ли количество бриллиантов  */
    private $showDiamondsCount;

    /** @var bool Показывать "product-card__set-trigger" */
    private $showSetTrigger;

    /** @var bool $frontendSort - Сортировка строго на фронте через js */
    private $frontendSort;

    /** @var bool $hideCartAddCheckbox - Флаг показа чекбокса добавления в корзину */
    private $hideCartAddCheckbox;

    /** @var bool $cart - Флаг, показывающий, что пользователь находится в корзине */
    private $cart;

    /** @var bool Ссылка на лот аукционна */
    private $linkToAuctionLot = false;

    /** @var bool Ссылка на /auctions/diamonds/ */
    private $linkToPacketNumber = false;

    /** @var \App\Models\Auctions\AuctionPBLot|null $lot - лот */
    private $lot;

    /** @var bool $hidePrice - Флаг скрытия цены */
    private $hidePrice;

    /** @var bool $constructor Флаг, указывающий на то, что пользователь находится в конструкторе ЮБИ */
    private $constructor;

    /** @var int $step Шаг конструктора */
    private $step;

    /** @var int $frameSkuId Идентификатор оправы */
    private $frameSkuId;

    /** @var bool $replace Флаг замены бриллианта в конструкторе */
    private $replace;

    /** @var int $orderNumber Порядковый номер в корзине (используется в B2B)  */
    private $orderNumber;

    /** @var bool $isAuction - Флаг нахождения на странице аукционов */
    private $isAuction = false;

    /**
     * Определяем параметры компонента
     *
     * @param array $arParams - Массив параметров
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $this->product = $arParams['product'];
        $this->checkedProductsInfo = $arParams['checked_products_info'];
        $this->view = $arParams['view'];
        $this->isInCart = $arParams['is_in_cart'];
        $this->showDiamondsCount = $arParams['show_diamonds_count'];
        $this->frontendSort = $arParams['frontend_sort'] ?? false;
        $this->hideCartAddCheckbox = $arParams['hide_cart_add_checkbox'] ?? false;
        $this->cart = $arParams['cart'] ?? false;
        $this->linkToAuctionLot = $arParams['linkToAuctionLot'];
        $this->linkToPacketNumber = $arParams['linkToPacketNumber'];
        $this->lot = $arParams['auction_lot'];
        $this->hidePrice = $arParams['hide_price'] ?? false;
        $this->showSetTrigger = $arParams['show_set_trigger'] !== false;
        $this->showAge = $arParams['show_age'] !==false;
        $this->constructor = $arParams['constructor'];
        $this->step = $arParams['step'];
        $this->frameSkuId = $arParams['frame_sku_id'];
        $this->replace = $arParams['replace'];
        $this->orderNumber = $arParams['orderNumber'];

        return $arParams;
    }

    /**
     * Запускаем компонент
     *
     * @return void
     * @throws LoaderException
     */
    public function executeComponent(): void
    {
        global $APPLICATION;

        $this->addBladePath(__DIR__);

        $this->isAuction = preg_match('/^\/(.*\/)?auctions(_pb)?\/.*$/i', $APPLICATION->GetCurPage());

        if ($this->linkToAuctionLot) {
            $link = $this->lot->getDetailPageUrl(false, true);
        } elseif ($this->linkToAuctionDiamonds) {
            $link = '/auctions/diamonds/' . $this->product->getPacketNumber();
        } elseif ($this->constructor) {
            if ($this->step == 1) {
                $link = sprintf(
                    '/%s/constructor/from-diamond/diamond/%s/?skuId=%s',
                    Config::BASE_DIR,
                    $this->product->getPacketNumber(),
                    $this->frameSkuId
                );
            } elseif ($this->step == 2) {
                $link = sprintf(
                    '/%s/constructor/from-frame/diamond/%s/?skuId=%s',
                    Config::BASE_DIR,
                    $this->product->getPacketNumber(),
                    $this->frameSkuId
                );
            }

            if ($this->replace && $this->frameSkuId) {
                $link .= '&frameSkuId=' . $this->frameSkuId . '&replace=true';
            }
        } else {
            $link = $this->product->getDetailPageUrl();
        }


        if (!$this->arParams['dontIncludeShapeImg'] && !$this->product->getPhotos()) {
            $shape = $this->product->getShape();
            if ($shape) {
                $shapeSampleImg = $shape->getSampleImgForShape();
                if ($shapeSampleImg) {
                    $this->product->setCustomPhotos([$shapeSampleImg]);
                }
            }
        }

        $this->arResult = [
            'lang' => Loc::loadLanguageFile(__FILE__, LanguageHelper::getLanguageVersion()),
            'product' => $this->product,
            'productShape' => $this->product->shape ?: $this->product->getShape(),
            'checkedProductsInfo' => $this->checkedProductsInfo,
            'currency' => Currency::getCurrentCurrency(),
            'view' => $this->view,
            'isInCart' => $this->isInCart,
            'showDiamondsCount' => $this->showDiamondsCount,
            'tagText' => (string) $this->arParams['tag_text'],
            'semitransparent' => (bool) $this->arParams['semitransparent'],
            'isB2c' => (bool) $this->arParams['b2c'],
            'isAuctionLot' => (bool) $this->arParams['is_auction_lot'],
            'button' => (string) $this->arParams['button'],
            'buttonClass' => (string) $this->arParams['button_class'],
            'onlyDesktop' => (bool) $this->arParams['only_desktop'],
            // если параметр не передан или он пустой, то контроль за способом отображения находится на стороне фронта
            'mode' => $this->arParams['mode'] ?? '',// === 'cell' ? 'cell' : 'table',
            'showSetTrigger' => $this->showSetTrigger,
            'showAge' => $this->showAge,
            'componentUniqueId' => $this->getComponentUniqueId(),
            'frontendSort' => $this->frontendSort,
            'hideCartAddCheckbox' => $this->hideCartAddCheckbox,
            'cart' => $this->cart,
            'link' => $link,
            'lot' => $this->lot,
            'hidePrice' => $this->hidePrice,
            'orderNumber' => $this->orderNumber,
            'showCreateDate' => false,
            'isLegalUser' => UserHelper::isLegalEntity(),
            'isAuction' => $this->isAuction,
            'showCreateDate' => false,
        ];

        $this->includeComponentTemplate();
    }

    /**
     * Этот параметр придуман исключительно для создания уникальности чекбоксов если один и тот же продукт появляется на странице дважды
     * @return int
     */
    private function getComponentUniqueId()
    {
        static $id = 0;
        $id++;
        return $id;
    }
}
