<?php

namespace App\Core\Jewelry\Filter;

use App\Core\BitrixProperty\Property;
use App\Core\Currency\Currency;
use App\Core\Jewelry\Enum\FilterUrlEnum;
use App\Helpers\JewelryHelper;
use App\Helpers\PriceHelper;
use App\Models\Catalog\Diamond;
use App\Models\Jewelry\Dicts\JewelryCollection;
use App\Models\Jewelry\Dicts\JewelryFamily;
use App\Models\Jewelry\Dicts\JewelryMetalColor;
use App\Models\Jewelry\JewelryBlank;
use App\Models\Jewelry\JewelryBlankSku;
use Bitrix\Main\Db\SqlQueryException;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Collection;

/**
 * Класс, описывающий логику работы фильтра оправ для конструктора ЮБИ
 * Class FramesFilter
 *
 * @package App\Core\Jewelry\Filter
 */
class FramesFilter extends FilterBaseAbstract
{
    /** @var Diamond|null  */
    protected $diamond = null;

    /**
     * FramesFilter constructor.
     *
     * @param string $sectionCode
     */
    public function __construct(string $sectionCode)
    {
        parent::__construct($sectionCode);

        if ($diamondId = e($_REQUEST['diamondId'])) {
            $this->diamond = Diamond::getById($diamondId);
        }
    }

    /**
     * Возвращает уникальную информацию для фильтра по типу
     *
     * @return array|mixed[]
     *
     * @throws Missing404Exception
     * @throws SqlQueryException
     */
    protected function getUniqueInfo(): array
    {
        /** @var JewelryFamily $jewelryFamily Семейство изделий */
        $jewelryFamily = $this->getJewelryFamily();
        $ranges = $this->getRanges($jewelryFamily);
        $metalColors = $this->getMetalColors();

        $propertyJewelry = new Property(JewelryBlank::iblockID());
        $propertyJewelry->addPropertyToQuery('COLLECTION_ID');
        $propertyJewelry->addPropertyToQuery('JEWELRY_FAMILY');
        $jewelryPropertiesInfo = $propertyJewelry->getPropertiesInfo();
        $collectionsWithJewelryQuery = db()->query(
            'SELECT DISTINCT PROPERTY_' . $jewelryPropertiesInfo['COLLECTION_ID']['PROPERTY_ID'] . ' as collection'
            . ' FROM b_iblock_element_prop_s' . JewelryBlank::iblockID()
            . ($jewelryFamily ? ' WHERE PROPERTY_' . $jewelryPropertiesInfo['JEWELRY_FAMILY']['PROPERTY_ID'] . ' = ' . $jewelryFamily->getId() : '')
        );
        $collectionsWithJewelry = [];
        while ($item = $collectionsWithJewelryQuery->fetch()) {
            $collectionsWithJewelry[] = $item['collection'];
        }

        $collectionsInfo = $this->getCollectionsInfo($collectionsWithJewelry);

        /** @var Property $propertySku Экземпляр класса для работы с битриксовыми свойствами торговых предложений */
        $propertySku = new Property(JewelryBlankSku::iblockID());
        $propertySku->addPropertyToQuery('SIZE');
        $propertySku->addPropertyToQuery('CML2_LINK');

        /** @var array|array[] $skuPropertiesInfo Массив, описывающий свойства */
        $skuPropertiesInfo = $propertySku->getPropertiesInfo();

        $sizesQuery = db()->query(
            'SELECT DISTINCT(sku.PROPERTY_' . $skuPropertiesInfo['SIZE']['PROPERTY_ID'] . ') AS size'
            . ' FROM b_iblock_element_prop_s' . JewelryBlankSku::iblockID() . ' sku'
            . ' INNER JOIN b_iblock_element_prop_s' . JewelryBlank::iblockID() . ' frame'
            . ' ON sku.PROPERTY_' . $skuPropertiesInfo['CML2_LINK']['PROPERTY_ID'] . ' = frame.IBLOCK_ELEMENT_ID'
            . ' WHERE frame.PROPERTY_' . $jewelryPropertiesInfo['JEWELRY_FAMILY']['PROPERTY_ID']
            . ($jewelryFamily ? ' = ' .  $jewelryFamily->getExternalID() : ' IS NOT NULL')
            . ' AND sku.PROPERTY_' . $skuPropertiesInfo['SIZE']['PROPERTY_ID'] . ' IS NOT NULL'
            . ' ORDER BY size ASC;'
        );
        $sizes = [];
        while ($size = $sizesQuery->fetch()) {
            $sizes[] = (float) $size['size'];
        }

        return [
            FilterUrlEnum::SIZES => $sizes,
            FilterUrlEnum::PRICE => ['min' => (int) $ranges['price_min'], 'max' => (int) $ranges['price_max']],
            FilterUrlEnum::DIAMONDS_COUNT => [
                'minimum' => (int) $ranges['diamonds_count_min'],
                'maximum' => (int) $ranges['diamonds_count_max']
            ],
            FilterUrlEnum::WEIGHT => [
                'min' => (float) $ranges['weight_min'],
                'max' => (float) $ranges['weight_max']
            ],
            'weightName' => 'Вес оправы, г',
            FilterUrlEnum::METALS_COLORS => $metalColors,
            'types' => $jewelryFamily ? $jewelryFamily->types : new Collection(),
            'popupClass' => 'filters--jewelry popup popup--filters',
            FilterUrlEnum::COLLECTIONS => $collectionsInfo,
            'ringsType' => $jewelryFamily && $jewelryFamily->getCode() === 'kolco',
            'priceTitle' => 'Цена за оправу'
        ];
    }

    /**
     * @return string[]
     */
    protected function getAllowedShapeCodes(): array
    {
        //Отображаем в фильтре только те формы, которые подходят к выбранному камню
        if ($this->diamond) {
            return [$this->diamond->getShapeID()];
        }

        return parent::getAllowedShapeCodes();
    }

    /**
     * @return string
     */
    protected function getCacheKey(): string
    {
        $key = parent::getCacheKey();
        if ($this->diamond) {
            $key .= '_'.$this->diamond->getShapeID();
        }

        return $key;
    }

    /**
     * @param JewelryFamily|null $jewelryFamily
     *
     * @return array|bool|false
     * @throws SqlQueryException
     */
    protected function getRanges(?JewelryFamily $jewelryFamily)
    {
        $property = new Property(JewelryBlank::iblockID());
        $property->addPropertyToQuery('PRICE');
        $property->addPropertyToQuery('DIAMONDS_COUNT');
        $property->addPropertyToQuery('WEIGHT');
        $property->addPropertyToQuery('JEWELRY_FAMILY');
        $propertiesInfo = $property->getPropertiesInfo();

        $frames = JewelryHelper::getCombinationFrames(!$jewelryFamily);

        $rangeQuery = 'SELECT MIN(`PROPERTY_'.$propertiesInfo['PRICE']['PROPERTY_ID'].'`+0) as price_min,'
            .' MAX(`PROPERTY_'.$propertiesInfo['PRICE']['PROPERTY_ID'].'`+0) as price_max,'
            .' MAX(PROPERTY_'.$propertiesInfo['DIAMONDS_COUNT']['PROPERTY_ID'].') as diamonds_count_max,'
            .' MIN(PROPERTY_'.$propertiesInfo['DIAMONDS_COUNT']['PROPERTY_ID'].') as diamonds_count_min,'
            .' MIN(`PROPERTY_'.$propertiesInfo['WEIGHT']['PROPERTY_ID'].'`+0) as weight_min,'
            .' MAX(`PROPERTY_'.$propertiesInfo['WEIGHT']['PROPERTY_ID'].'`+0) as weight_max'
            .' FROM b_iblock_element_prop_s'.JewelryBlank::iblockID()
            .' WHERE 1=1'
            . (!empty($frames) ? ' AND IBLOCK_ELEMENT_ID IN (' . implode(',', $frames) . ')' : '')
        ;

        if ($jewelryFamily) {
            //Фильтруем по выбранной форме
            $rangeQuery .= ' AND PROPERTY_'.$propertiesInfo['JEWELRY_FAMILY']['PROPERTY_ID'].'='.$jewelryFamily->getExternalID()
                ;
        }

        $ranges = db()->query($rangeQuery)->fetch();

        $ranges['price_min'] = PriceHelper::getPriceInSpecificCurrency(
            $ranges['price_min'],
            Currency::getCurrentCurrency()
        );

        $ranges['price_max'] = PriceHelper::getPriceInSpecificCurrency(
            $ranges['price_max'],
            Currency::getCurrentCurrency()
        );

        //Хак для слайдера на фронте
        if ($ranges['diamonds_count_max']==$ranges['diamonds_count_min']) {
            $ranges['diamonds_count_max'] = $ranges['diamonds_count_min']+1;
        }

        return $ranges;
}

    /**
     * @return array
     * @throws SqlQueryException
     */
    protected function getMetalColors(): array
    {
        $property = new Property(JewelryBlankSku::iblockID());
        $property->addPropertyToQuery('METAL_ID');
        $property->addPropertyToQuery('METAL_COLOR_ID');
        $property->addPropertyToQuery('CML2_LINK');
        $propertiesInfo = $property->getPropertiesInfo();

        $sql            = 'SELECT DISTINCT p.PROPERTY_'.$propertiesInfo['METAL_COLOR_ID']['PROPERTY_ID'].' as color,'
            .' p.PROPERTY_'.$propertiesInfo['METAL_ID']['PROPERTY_ID'].' as metal'
            .' FROM b_iblock_element_prop_s'.JewelryBlankSku::iblockID().' p'
            .' INNER JOIN b_iblock_element as e ON e.ID=p.PROPERTY_'.$propertiesInfo['CML2_LINK']['PROPERTY_ID']
            .' WHERE e.ACTIVE="Y";';
        $metalColorPair = db()->query($sql)->fetchAll();
        $metalColorPair = collect($metalColorPair);
        $metalColors    = JewelryMetalColor::getUniquePairs($metalColorPair);

        return $metalColors;
    }

    /**
     * @return JewelryFamily|null
     * @throws Missing404Exception
     */
    protected function getJewelryFamily()
    {
        $jewelryFamily = null;
        if ($this->sectionCode && $this->sectionCode != 'all') {
            $jewelryFamily = JewelryHelper::getJewelryFamilyByCode(e($this->sectionCode), ['types']);
            if (!$jewelryFamily) {
                throw new Missing404Exception();
            }
        }

        return $jewelryFamily;
    }

    /**
     * Возвращает массив коллекций, у которых есть изделия
     *
     * @param string[] $collectionsWithProducts
     *
     * @return array[]
     */
    protected function getCollectionsInfo(array $collectionsWithProducts): array
    {
        /** @var Collection|JewelryCollection[] $collections Коллекции украшений */
        $collections = JewelryCollection::filter(['UF_XML_ID' => $collectionsWithProducts])->getList();
        $collectionsInfo = $collections->pluck('UF_NAME_' . $this->language, 'UF_XML_ID')->toArray();
        foreach ($collectionsInfo as $key => $value) {
            $collectionsInfo[$key] = [
                'key' => str_replace(' ', '_', $value),
                'value' => $value
            ];
        }

        return $collectionsInfo;
    }
}
