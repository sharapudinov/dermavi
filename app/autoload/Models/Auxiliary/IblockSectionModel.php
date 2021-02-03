<?php

namespace App\Models\Auxiliary;

use Arrilot\BitrixModels\Models\SectionModel;
use Arrilot\BitrixModels\Queries\BaseQuery;

/**
 * Класс-модель для раздела инфоблока
 * Class IblockSectionModel
 * @package App\Models\Auxiliary
 * @property IblockSectionModel $sectionEntity - раздел элемента
 */
class IblockSectionModel extends SectionModel
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
     * Получить идентификатор раздела
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
     * Получить идентификатор родительского раздела
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
        return (int)$this['PICTURE'];
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
     * Получить ссылку на раздел
     * @return int
     */
    public function getSectionPageUrl(): string
    {
        return (string)$this['SECTION_PAGE_URL'];
    }
    
    /**
     * Устанавливает связь с родительским разделом инфоблока
     * @return BaseQuery
     */
    public function sectionEntity(): BaseQuery
    {
        return $this->hasOne(IblockSectionModel::class, 'ID', 'IBLOCK_SECTION_ID');
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
