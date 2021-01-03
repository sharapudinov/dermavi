<?php

namespace App\Models\Auxiliary;

use Arrilot\BitrixModels\Models\ElementModel;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель для элемента инфоблока
 * Class IblockElementModel
 * @package App\Models\Auxiliary
 * @property IblockSectionModel $sectionEntity - раздел элемента
 */
class IblockElementModel extends ElementModel
{
    /**
     * Идентификатор инфоблока
     * @var int
     */
    public static $iblockId;
    /**
     * Символьный код инфоблока
     * @var string
     */
    public static $iblockCode;
    
    /**
     * Получить идентификатор инфоблока
     * @return int
     */
    public static function iblockId(): int
    {
        return (int)static::$iblockId ?: iblock_id(static::$iblockCode);
    }
    
    /**
     * Получить идентификатор элемента
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }
    
    /**
     * Получить идентификатор инфоблока
     * @return int
     */
    public function getIblockId(): int
    {
        return (int)$this['IBLOCK_ID'];
    }
    
    /**
     * Получить символьный код инфоблока
     * @return string
     */
    public function getIblockCode(): string
    {
        return (string)$this['IBLOCK_CODE'];
    }
    
    /**
     * Получить идентификатор раздела
     * @return int
     */
    public function getSectionId(): int
    {
        return (int)$this['IBLOCK_SECTION_ID'];
    }
    
    /**
     * Получить идентификатор картинки для анонса
     * @return int
     */
    public function getPreviewPicture(): int
    {
        return (int)$this['PREVIEW_PICTURE'];
    }
    
    /**
     * Получить идентификатор детальной картинки
     * @return int
     */
    public function getDetailPicture(): int
    {
        return (int)$this['DETAIL_PICTURE'];
    }
    
    /**
     * Получить ссылку на элемент
     * @return string
     */
    public function getDetailPageUrl(): string
    {
        return (string)$this['DETAIL_PAGE_URL'];
    }
    
    /**
     * Устанавливает связь с разделом инфоблока
     * @return BaseQuery
     */
    public function sectionEntity(): BaseQuery
    {
        return $this->hasOne(IblockSectionModel::class, 'ID', 'IBLOCK_SECTION_ID');
    }
    
    /**
     * Получить ссылку на список элементов
     * @return string
     */
    public function getListPageUrl(): string
    {
        return (string)$this['LIST_PAGE_URL'];
    }
    
    /**
     * Получаем дату начала активности в формате ДД.ММ.ГГГГ
     * @return string
     */
    public function getActiveFrom(): string
    {
        return (string)$this['ACTIVE_FROM'];
    }
    
    /**
     * Получаем дату окончания активности в формате ДД.ММ.ГГГГ
     * @return string
     */
    public function getActiveTo(): string
    {
        return (string)$this['ACTIVE_TO'];
    }
    
    /**
     * Получить внешний идентификатор
     * @return string
     */
    public function getXmlId(): string
    {
        return (string)$this['XML_ID'];
    }
}
