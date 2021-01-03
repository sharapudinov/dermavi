<?php

namespace App\Core\Catalog\ViewedProducts;

use App\Core\System\Singleton;
use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Main\Loader;

Loader::includeModule('sale');
Loader::IncludeModule('catalog');

/**
 * Class ViewContent
 *
 * @package App\Core\Catalog\ViewedProducts
 */
class ViewedProduct extends Singleton
{
    /**
     * Пометить товар как просмотренный
     *
     * @param $productId
     * @param null $skuId
     * @param ViewedProductTypeInterface $type Тип товара
     *
     * @return bool
     */
    public function markAsViewed($productId, $skuId = null, ViewedProductTypeInterface $type)
    {
        return CatalogViewedProductTable::refresh(
            $productId,
            \CSaleBasket::GetBasketUserID(),
            SITE_ID,
            $skuId,
            $type->getType()
        ) != -1;
    }

    /**
     * Получить последние просмотренные
     *
     * @param int  $limit
     * @param null $excludeId
     * @param ViewedProductTypeInterface $type Тип товара
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getList($limit = 6, $excludeId = null, ViewedProductTypeInterface $type)
    {
        $viewedIterator = CatalogViewedProductTable::getList([
            'select' => ['ELEMENT_ID'],
            'filter' => [
                '=FUSER_ID'       => \CSaleBasket::GetBasketUserID(),
                '=SITE_ID'        => SITE_ID,
                '=RECOMMENDATION' => $type->getType()
            ],
            'order'  => ['DATE_VISIT' => 'DESC'],
            'limit'  => $limit,
        ]);

        $ids = [];
        while ($viewedProduct = $viewedIterator->fetch()) {
            $id = (int)$viewedProduct['ELEMENT_ID'];
            if ($id==$excludeId) {
                continue;
            }
            $ids[] = $id;
        }

        return $ids;
    }
}
