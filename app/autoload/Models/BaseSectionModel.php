<?php

namespace App\Models;

use App\Helpers\LanguageHelper;
use Arrilot\BitrixModels\Models\D7Model;
use Arrilot\BitrixModels\Models\ElementModel;
use Arrilot\BitrixModels\Models\SectionModel;
use CFile;

/**
 * Class BaseD7Model
 *
 * @package App\Models
 */
class BaseSectionModel extends SectionModel
{
    /** @var string Символьный код инфоблока */
    const IBLOCK_CODE = '';

    /**
     * Возвращает идентификатор инфоблока.
     *
     * @return int
     */
    public static function iblockID(): int
    {
        return (int)iblock_id(static::IBLOCK_CODE);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }

    /**
     * Получить название подкатегории
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this['NAME'];
    }

    /**
     * Возвращает название раздела с учетом мультиязычности
     *
     * @return string
     */
    public function getMultiLanguageName(): string
    {
        return LanguageHelper::getSectionMultilingualFieldValue($this, 'NAME') ?? $this->getName();
    }

    /**
     * Получить код подкатегории
     *
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this['CODE'];
    }

    /**
     * Ссылка на картинку подкатегории
     *
     * @return string|null
     */
    public function getDetailPicture(): ?string
    {
        return CFile::GetPath($this['DETAIL_PICTURE']);
    }

    /**
     * Получить оригинальное значение свойства 'DETAIL_PICTURE' (ID файла)
     *
     * @return int|null
     */
    public function getOriginalDetailPicture(): ?int
    {
        return $this->original['DETAIL_PICTURE'];
    }

    /**
     * Описание раздела
     *
     * @return string|null
     */
    public function getDetailText(): ?string
    {
        return $this['DESCRIPTION'];
    }
}
