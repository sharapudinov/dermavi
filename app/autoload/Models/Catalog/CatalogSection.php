<?php

namespace App\Models\Catalog;

use Arrilot\BitrixModels\Models\SectionModel;

/**
 * Класс-модель описывающая раздел бриллианта
 * Class CatalogSection
 * @package App\Models\Catalog
 */
class CatalogSection extends SectionModel
{
    /** @var string - Символьный код раздела "Для юр. лиц" */
    public const FOR_LEGAL_PERSONS_SECTION_CODE = 'FOR_LEGAL_PERSONS';

    /** @var string - Символьный код раздела "Для физ. лиц" */
    public const FOR_PHYSIC_PERSONS_SECTION_CODE = 'FOR_PHYSIC_PERSONS';

    /** @var string - Символьный код раздела "Аукционные товары */
    public const FOR_AUCTIONS_SECTION_CODE = 'FOR_AUCTIONS';

    /**
     * Возвращает идентификатор инфоблока
     *
     * @return int
     */
    public static function iblockId() : int
    {
        return iblock_id(Diamond::IBLOCK_CODE);
    }

    /**
     * Возвращает идентификатор раздела
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this['ID'];
    }

    /**
     * Возвращает символьный код раздела
     *
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this['CODE'];
    }
}
