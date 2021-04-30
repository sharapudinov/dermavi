<?php


namespace App\Models\Catalog;


use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class CatalogSku extends Catalog
{
    /** @var string Символьный код инфоблока предложений */
    const IBLOCK_CODE = 'catalog_offers';

    /** @var string Символьный код  API инфоблока предложений */
    const IBLOCK_API_CODE = 'offers';

    /** @var string - InitDir кеша для каталога */
    const CATALOG_CACHE_INIT_DIR = 'offers';

    /**
     * Возвращает коды полей элемента инфоблока
     */
    public static function getFieldsCode()
    {
        return [
            'ID',
            'CODE',
            'NAME',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE'
        ];
    }

    /**
     * Возвращает коды свойств элемента инфоблока
     */

    public static function getPropertiesCode()
    {
        return [
            'MORE_PHOTO_FILE' => 'MORE_PHOTO.FILE.ID',
            'ARTICUL'    => 'ARTNUMBER.VALUE',
            'COLOR_NAME' => 'COLOR.UF_NAME',
            'COLOR_FILE' => 'COLOR.UF_FILE',
            'COLOR_ID' => 'COLOR.ID',
            'VOLUME_VALUE'     => 'VOLUME.ITEM.VALUE',
        ];
    }

    /**
     * Возвращает сущность запроса в базу данных.
     *
     * @return \Bitrix\Main\ORM\Query\Query::
     */
    public static function query()
    {
        Loader::includeModule('sale');
        //HL Color_ref
        $hldata = HighloadBlockTable::getById(1)->fetch();
        $hlentity = HighloadBlockTable::compileEntity($hldata);

        $price_list = new Reference(
            'PRICE_LIST',
            PriceTable::class,
            Join::on('this.ID', 'ref.PRODUCT_ID')
        );

        $product = new Reference(
            'PRODUCT',
            ProductTable::class,
            Join::on('this.CML2_LINK.VALUE', 'ref.ID')
        );
        $color = new Reference(
            'COLOR',
            $hlentity,
            Join::on('this.COLOR_REF.VALUE', 'ref.UF_XML_ID')
        );

        $query = static::getIblockEntityInstace()->getEntityDataClass()::query();
        $query
            ->registerRuntimeField($price_list)
            ->registerRuntimeField($product)
            ->registerRuntimeField($color)
            ->setSelect(static::getSelect());
        return $query;
    }
}
