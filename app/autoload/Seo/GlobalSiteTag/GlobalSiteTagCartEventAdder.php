<?php

namespace App\Seo\GlobalSiteTag;

use App\Core\Sale\View\OrderItemViewModel;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\JewelryConstructorReadyProduct;
use App\Models\Jewelry\JewelrySku;
use Illuminate\Support\Collection;

/**
 * Класс для добавления событий add_to_cart или purchase к отправке в gtag
 * Class GlobalSiteTagCartEventAdder
 * @package App\Seo\GlobalSiteTag
 */
class GlobalSiteTagCartEventAdder
{
    /** @var string */
    private $eventName;

    /** @var int */
    private $cartCost;

    /** @var string */
    private $currencyCode;

    /** @var OrderItemViewModel[]|Collection */
    private $basketItems;

    /**
     * GlobalSiteTagCartEventAdder constructor.
     * @param string $eventName
     * @param int $cartCost
     * @param string $currencyCode
     * @param Collection|OrderItemViewModel[] $basketItems
     */
    public function __construct(
        string $eventName,
        int $cartCost,
        string $currencyCode,
        Collection $basketItems
    ) {
        $this->eventName = $eventName;
        $this->cartCost = $cartCost;
        $this->currencyCode = $currencyCode;
        $this->basketItems = $basketItems;
    }

    /**
     * Добавляет событие add_to_cart или purchase к отправке в gtag
     */
    public function run(): void
    {
        $items = $this->getItems();

        if ($items->isEmpty()) {
            return;
        }

        (new GlobalSiteTagHandler)->addEvent(
            (new GlobalSiteTagEvent)
                ->setName($this->eventName)
                ->setValue(sprintf('%s %s', $this->cartCost, $this->currencyCode))
                ->setItems($items->toArray())
        );
    }

    /**
     * Формирует список товаров для передачи в gtag
     *
     * @return Collection
     */
    private function getItems(): Collection
    {
        $items = new Collection();

        foreach ($this->basketItems as $basketItem) {
            /**
             * Бриллиант
             */

            $diamond = $basketItem->getDiamond();

            if ($diamond instanceof Diamond) {
                $items->push([
                    'id' => $diamond->getIDForFeed(),
                    'google_business_vertical' => 'retail',
                ]);
            }

            /**
             * Ювелирное изделие
             */

            $jewelrySku = $basketItem->getJewelrySku();

            if ($jewelrySku instanceof JewelrySku) {
                $items->push([
                    'id' => $jewelrySku->getIdForFeed(),
                    'google_business_vertical' => 'retail',
                ]);
            }

            /**
             * Готовое изделие (конструктор ЮБИ)
             */

            $readyProduct = $basketItem->getCombination();

            if (
                $readyProduct instanceof JewelryConstructorReadyProduct
                && $readyProduct->blankSku
                && $readyProduct->diamonds
                && $readyProduct->diamonds->isNotEmpty()
            ) {
                $items->push([
                    'id' => $readyProduct->getIdForGtag(),
                    'google_business_vertical' => 'retail',
                ]);
            }
        }

        return $items;
    }
}
