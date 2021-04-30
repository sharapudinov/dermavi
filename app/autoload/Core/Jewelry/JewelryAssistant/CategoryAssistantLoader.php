<?php

namespace App\Core\Jewelry\JewelryAssistant;

use App\Core\BitrixProperty\Property;
use App\Helpers\JewelryHelper;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelrySku;
use Arrilot\BitrixModels\Queries\BaseQuery;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий получение ЮБИ для раздела в помощнике по стилю
 * Class CategoryAssistantLoader
 *
 * @package App\Core\Jewelry\JewelryAssistant
 */
class CategoryAssistantLoader extends AssistantLoaderAbstract
{
    /**
     * Возвращает данные для компонента
     *
     * @return array|mixed[]
     */
    public function getData(): array
    {
        $preferredStyles = $this->getPreferredStyles();

        /** @var JewelrySku $query */
        $query = JewelrySku::query();

        /** @var Collection|JewelrySku[] $skus */
        $skus = $query->forNoSaleCatalog()->filter([
                'PROPERTY_SEX_ID' => e($this->arParams['gender'])
            ])
            ->sort('SORT', 'ASC')
            ->getList();

        $skusIds = $skus->pluck('ID')->toArray();

        $sellingAvailable = Property::getListPropertyValue(JewelrySku::iblockID(), 'SELLING_AVAILABLE', 'Y');

        $collections = [];
        foreach ($preferredStyles as $preferredStyle) {
            $collections = array_merge($collections, $preferredStyle->collections->pluck('UF_XML_ID')->toArray());
        }

        $productsQuery = Jewelry::fromSectionWithCode(e($this->arParams['category']))
            ->filter([
                'PROPERTY_COLLECTION_ID' => $collections
            ])
            ->with(['activeSkus' => function (BaseQuery $query) use ($skusIds, $sellingAvailable) {
                $query->filter(['ID' => $skusIds, 'PROPERTY_SELLING_AVAILABLE' => $sellingAvailable->getVariantId()]);
            }]);

        /** @var Collection|Jewelry[] $products Коллекция моделей ювелирных украшений */
        $products = $productsQuery->getList();
        JewelryHelper::removeInactive($products);

        $framesQuery = JewelryBlank::active()
            ->filter([
                'PROPERTY_SEX_ID' => e($this->arParams['gender']),
                'PROPERTY_COLLECTION_ID' => $collections
            ])
            ->with('activeSkus')
            ->sort('SORT', 'ASC');

        $frames = $framesQuery->forPage($this->arParams['page'], self::PAGE_COUNT)->getList();

        if ($this->arParams['sort']['option'] && $this->arParams['sort']['direction']) {
            $products = JewelryHelper::sortJewelry(
                $products,
                $this->arParams['sort']['option'],
                $this->arParams['sort']['direction']
            );

            $frames = JewelryHelper::sortJewelry(
                $frames,
                $this->arParams['sort']['option'],
                $this->arParams['sort']['direction']
            );
        }

        $jewelrySliced = $products->slice(
            (($this->arParams['page'] - 1) * self::PAGE_COUNT), self::PAGE_COUNT
        );

        return [
            'jewelry' => $jewelrySliced,
            'currentCategory' => $jewelrySliced->isNotEmpty() ? $jewelrySliced->first()->category->getCode() : null,
            'totalJewelryCount' => $products->count(),
            'totalFramesCount' => $framesQuery->count(),
            'frames' => $frames
        ];
    }
}
