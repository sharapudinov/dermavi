<?php

namespace App\Core\Jewelry;

use App\Core\BitrixProperty\Property;
use App\Core\System\Singleton;
use App\Helpers\TTL;
use App\Models\Jewelry\Jewelry;
use App\Models\Jewelry\JewelrySection;

/**
 * Хелпер для работы с подразделом колец
 * Class RingHelper
 *
 * @package App\Core\Jewelry
 */
class RingHelper extends Singleton
{
    public const RING_TYPE_WEDDING = 'На свадьбу';

    public const RING_TYPE_ENGAGEMENT = 'На помолвку';

    /** @var JewelrySection */
    protected $section = null;

    /** @var array */
    protected $types = null;

    /**
     * Объект подраздела колец
     *
     * @return JewelrySection
     */
    public function getSection(): JewelrySection
    {
        if (!$this->section) {
            $this->section = JewelrySection::filter(['UF_XML_ID' => JewelrySection::RING_TYPE_XML_ID])
                ->cache(TTL::WEEK)
                ->first();
        }

        return $this->section;
    }

    /**
     * Список типов колец
     *
     * @return array
     */
    public function getTypes(): array
    {
        if (!$this->types) {
            $property = new Property(Jewelry::iblockID());
            $property->addPropertyToQuery('TYPE');

            $this->types = $property->getPropertiesInfo();
        }

        return $this->types;
    }

    /**
     * Возвращает ссылку на предустановленый фильтр по указанному типу колец
     *
     * @param string $ringType
     *
     * @return string
     */
    public function getUrlByRingType(string $ringType): string
    {
        $typeId = $this->getTypes()['TYPE']['VALUES'][$ringType] ?? null;
        if (!$typeId) {
            throw new \LogicException('Unknown ring type ', $ringType);
        }

        return $this->getSection()->getSectionUrl().'?type[0]='.$typeId;
    }

}
