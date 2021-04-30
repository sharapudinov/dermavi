<?php

namespace App\Core\Jewelry\Dto;

use App\Core\LazyLoadDto;
use App\Helpers\JewelryHelper;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelrySection;
use App\Models\Jewelry\JewelrySku;
use Illuminate\Support\Collection;

/**
 * Модель представления ЮИ.
 * Class JewelryViewModel
 *
 * @package App\Core\Sale\History
 * @property string                     $name                  Название
 * @property string                     $category              Название подраздела
 * @property string                     $categoryUrl           Ссылка на подраздел
 * @property string                     $collection            Коллекция
 * @property int                        $collectionId          Id Коллекции
 * @property array                      $weights               Массив весов
 * @property string                     $number                Артикул
 * @property array                      $metals                Массив металов
 * @property array                      $sizes                 Массив размеров
 * @property int                        $id                    Id товара
 * @property string                     $code                  Код товара (бирка)
 * @property bool                       $isRing                Находится ли товар в подразделе колец
 * @property JewelrySkuDto[]|Collection $variants              Вставки
 */
class JewelryDto extends LazyLoadDto
{

    /**
     * JewelryDto constructor.
     *
     * @param string|Jewelry $codeOrModel
     */
    public function __construct($codeOrModel)
    {
        if (is_string($codeOrModel)) {
            /** @var Jewelry $model */
            $model = Jewelry::getByCode($codeOrModel);
        } else {
            $model = $codeOrModel;
        }

        if (!$model) {
            throw new \InvalidArgumentException('Jewelry not found');
        }

        /** @var JewelrySku[]|Collection $skus */
        $skus = $model->activeSkus;
        $availableSkus = $skus->filter(function (JewelrySku $sku) {
            return $sku->isAvailableForSelling();
        });

        //Тут данные которые можно кешировать, остальные через LazyLoad
        parent::__construct([
            'id'           => $model->getId(),
            'code'         => $model->getExternalId(),
            'number'       => $model->getCode(),
            'name'         => $model->getName(),
            'collectionId' => $model->collection ? $model->collection->getId() : '',
            'collection'   => $model->collection ? $model->collection->getName() : '',
            'weights'      => $availableSkus->map->getDiamondsWeight()->filter()->sort()->all(),
            'metals'       => JewelryHelper::getMetals($skus),
            'sizes'        => $availableSkus->map->getSize()->filter()->unique()->sort()->all(),
            'variants'     => $skus->mapInto(JewelrySkuDto::class),
            'category'     => $model->category->getMultiLanguageName(),
            'categoryUrl'  => $model->category->getSectionUrl(),
            'isRing'       => $model->category->getXmlId() == JewelrySection::RING_TYPE_XML_ID,
            'isEngravingDisabled' => $model->isEngravingDisabled(),
        ]);
    }
}
