<?php

namespace App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet;

/**
 * Trait WithSmartPropertySectionIdTrait
 *
 * @package App\Filtration\Traits\FilterFieldDtoBuilder\BitrixFacet
 */
trait WithSmartPropertySectionIdTrait
{
    /** @var int */
    protected $smartPropertySectionId = 0;

    /**
     * @return int
     */
    public function getSmartPropertySectionId(): int
    {
        return $this->smartPropertySectionId;
    }

    /**
     * @param int $smartPropertySectionId
     * @return static
     */
    public function setSmartPropertySectionId(int $smartPropertySectionId)
    {
        $this->smartPropertySectionId = $smartPropertySectionId;

        return $this;
    }
}
