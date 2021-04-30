<?php

namespace App\Core\Jewelry\JewelryAssistant;

use App\Core\BitrixProperty\Property;
use App\Helpers\JewelryHelper;
use App\Models\Jewelry\Dicts\JewelryAssistantStyle;
use App\Models\Jewelry\Dicts\JewelrySex;
use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelrySku;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий получение ЮБИ для первой загрузки помощника по стилю
 * Class DefaultAssistantLoader
 *
 * @package App\Core\Jewelry\JewelryAssistant
 */
class DefaultAssistantLoader extends AssistantLoaderAbstract
{
    /**
     * Возвращает данные для компонента
     *
     * @return array|mixed[]
     */
    public function getData(): array
    {
        /** @var JewelrySex $gender */
        $gender = JewelrySex::filter(['=UF_XML_ID' => $this->arParams['gender']])->first();

        $preferredStyles = $this->getPreferredStyles();

        $sellingAvailable = Property::getListPropertyValue(JewelrySku::iblockID(), 'SELLING_AVAILABLE', 'Y');

        /** @var JewelrySku $query */
        $query = JewelrySku::query();
        /** @var Collection|JewelrySku[] $skus */
        $skus = $query->forNoSaleCatalog()->filter([
                'ACTIVE' => 'Y',
                'PROPERTY_SEX_ID' => e($this->arParams['gender']),
                'PROPERTY_SELLING_AVAILABLE' => $sellingAvailable->getVariantId()
            ])
            ->with('jewelry.category')
            ->sort('SORT', 'ASC')
            ->getList();

        $categories = new Collection();
        $jewelry = new Collection();

        $collections = [];
        foreach ($preferredStyles as $preferredStyle) {
            $collections = array_merge($collections, $preferredStyle->collections->pluck('UF_XML_ID')->toArray());
        }
        foreach ($skus as $sku) {
            if (in_array($sku->jewelry->collection->getExternalID(), $collections)) {
                $categories->push($sku->jewelry->category);
                if ($categories->first()->getId() == $sku->jewelry->category->getId()) {
                    $jewelry->push($sku->jewelry);
                }
            }
        }
        JewelryHelper::removeInactive($jewelry);

        $jewelry = JewelryHelper::sortJewelry(
            $jewelry,
            'SORT',
            'ASC'
        );

        $jewelry = $jewelry->unique();
        $categories = $categories->unique();

        $jewelrySliced = $jewelry->slice(0, self::PAGE_COUNT);
        $framesQuery = JewelryBlank::filter([
                'PROPERTY_GENDER_ID' => $gender->getExternalID(),
                'PROPERTY_COLLECTION_ID' => $collections
            ])
            ->with('activeSkus')
            ->sort('SORT', 'ASC');


        $frames = $framesQuery->forPage(1, self::PAGE_COUNT)->getList();

        return [
            'categories' => $categories,
            'jewelry' => $jewelrySliced,
            'totalJewelryCount' => $jewelry->count(),
            'currentCategory' => $jewelrySliced->isNotEmpty() ? $jewelrySliced->first()->category->getCode() : null,
            'frames' => $frames,
            'totalFramesCount' => $framesQuery->count(),
            'preferredStyles' => $preferredStyles,
            'gender' => $gender
        ];
    }
}
