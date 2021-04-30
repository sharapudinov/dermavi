<?php

namespace App\Models\Catalog;


use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\Elements\ElementCatalogTable;
use Bitrix\Iblock\EO_Property_Collection;
use Bitrix\Iblock\Iblock;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;


/**
 * Класс-модель бриллианта.
 * Class Product
 * @package App\Models\Catalog
 *
 */
class Catalog
{
    /** Время кеширования запросов получения связанных сущностей, задается в минутах (см. BaseQuery::rememberInCache) */
    protected const REL_CACHE_TTL = 5;

    /** @var string Символьный код инфоблока */
    const IBLOCK_CODE = 'catalog';

    /** @var string Символьный код  API инфоблока */
    const IBLOCK_API_CODE = 'Catalog';

    /** @var Iblock */
    public static $iblockEntity = null;

    /** @var string - InitDir кеша для каталога */
    const CATALOG_CACHE_INIT_DIR = 'Catalog';


    /**
     * Возвращает идентификатор инфоблока.
     *
     * @return string
     */
    public static function iblockID(): int
    {
        return (int)iblock_id(static::IBLOCK_CODE);
    }


    /**
     * Возвращает инстанс ORM-сущности инфоблока.
     *
     * @return Iblock
     */

    public static function getIblockEntityInstace()
    {
        Loader::includeModule('iblock');
        return Iblock::wakeUp(static::iblockID());
    }

    /**
     * Возвращает сущность запроса в базу данных.
     *
     * @return \Bitrix\Main\ORM\Query\Query
     */
    public static function query()
    {
        Loader::includeModule('sale');

        $price_list = new Reference(
            'PRICE_LIST',
            PriceTable::class,
            Join::on('this.ID', 'ref.PRODUCT_ID')
        );

        $product = new Reference(
            'PRODUCT',
            ProductTable::class,
            Join::on('this.ID', 'ref.ID')
        );
        $query = self::getIblockEntityInstace()->getEntityDataClass()::query();
        $query
            ->registerRuntimeField($price_list)
            ->registerRuntimeField($product)
            ->setSelect(static::getSelect());
        return $query;
    }

    /**
     * Возвращает коллекцию свойств.
     *
     * @return EO_Property_Collection
     */
    public static function getProperties()
    {
        return PropertyTable::getList(
            [
                'filter' => ['IBLOCK_ID' => static::iblockID(), 'ACTIVE' => 'Y'],
            ]
        )->fetchCollection();
    }

    /**
     * Переопределенный метод, возвращающий неймспейс модели раздела каталога
     *
     * @return string
     */
    public static function sectionModel(): string
    {
        return CatalogSection::class;
    }

    /**
     * Переопределенный метод, возвращающий неймспейс модели тороговых предложений
     *
     * @return string
     */
    public static function skuModel(): string
    {
        return CatalogSku::class;
    }


    /**
     * @inheritDoc
     */
    public function getProductTypeCode(): string
    {
        return 'cosmetic';
    }

    /**
     * Возвращает модель раздела элемента
     *
     * @param bool $load
     *
     * @return CatalogSection
     */
    public function section($load = false): CatalogSection
    {
        $CatalogSection = parent::section($load);

        // @todo !!! parent::section() может вернуть и false !!!
        // @todo Пока такой костыль, потом нужно будет выполнить аудит и либо поправить тип, либо исключение кидать
        if (!$CatalogSection) {
            $CatalogSection = new CatalogSection((int)$this->getSectionId());
        }

        return $CatalogSection;
    }

    /**
     * Возвращает коды полей элемента инфоблока
     */
    public static function getFieldsCode()
    {
        return [
            'ID',
            'CODE',
            'IBLOCK_ID',
            'NAME',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_TEXT',
            'DETAIL_PICTURE'
        ];
    }

    /**
     * Возвращает коды свойств элемента инфоблока
     */

    public static function getPropertiesCode()
    {
        return [
            'ARTNUMBER',
            'MANUFACTURER',
            'SALELEADER',
            'NEWPRODUCT',
            'VIDEO',
            'STRUCTURE',
            'APPLICATION',
            'MORE_PHOTO.FILE',
            'RECOMMEND.ELEMENT'
        ];
    }

    /**
     * Возвращает коды полей торгового каталога
     */
    public static function getProductCode()
    {
        return [
            "QUANTITY" => "PRODUCT.QUANTITY",
            "WEIGHT"   => 'PRODUCT.WEIGHT',
            "TYPE"     => 'PRODUCT.TYPE',
        ];
    }

    public static function getSelect()
    {
        return array_merge(
            static::getFieldsCode(),
            static::getPropertiesCode(),
            static::getProductCode()
        );
    }

}
