<?php

namespace App\Core\Traits\Sale;

use App\Models\Auxiliary\Sale\BitrixBasketItem;
use Illuminate\Support\Collection;

/**
 * Класс-трейт для работы с корзиной
 * Trait CartTrait
 * @package App\Core\Traits\Sale
 */
trait CartTrait
{

    /**
     * Загружает товары для корзины
     *
     * @param array $basketItemsIds
     * @return void
     */
    protected function loadBasketItems(array $basketItemsIds): void
    {
        if ($basketItemsIds) {
            /** @var \Illuminate\Support\Collection|BitrixBasketItem[] $basketItems - Коллекция товаров в корзине */
            $this->basketItems = BitrixBasketItem::filter(['ID' => $basketItemsIds])
                ->with(['properties'])
                ->getList();
        } else {
            $this->basketItems = new Collection();
        }
    }
    /**
     * @param int $id
     * @return BitrixBasketItem|null
     */
    protected function getBitrixBasketItemById(int $id): ?BitrixBasketItem
    {
        return $this->getBasketItems()[$id] ?? null;
    }

    /**
     * @param int $id
     * @return static
     */
    protected function forgetBitrixBasketItem(int $id)
    {
        $this->getBasketItems()->forget($id);

        return $this;
    }

    /**
     * Передает ссылку на коллекцию товаров в корзине (без услуг)
     * Переопределяет метод базового класса - BasketItemCollection //@todo Это так и было задумано?
     *
     * @return Collection|BitrixBasketItem[]
     */
    public function getBasketItems(): Collection
    {
        if ($this->basketItems === null) {
            $this->basketItems = new Collection();
        }

        return $this->basketItems;
    }

    /**
     * Передает ссылку на коллекцию гравировок
     *
     * @return Collection|BitrixBasketItem[]
     */
    public function getEngravings(): Collection
    {
        if ($this->engravings === null) {
            $this->engravings = new Collection();
        }

        return $this->engravings;
    }

    /**
     * Передает ссылку на коллекцию сертификатов
     *
     * @return Collection|BitrixBasketItem[]
     */
    public function getCertificates(): Collection
    {
        if ($this->certificates === null) {
            $this->certificates = new Collection();
        }

        return $this->certificates;
    }
}
