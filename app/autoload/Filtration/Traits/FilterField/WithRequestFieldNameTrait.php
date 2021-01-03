<?php

namespace App\Filtration\Traits\FilterField;

/**
 * Trait WithRequestFieldNameTrait
 *
 * @package App\Filtration\Traits\FilterField
 */
trait WithRequestFieldNameTrait
{
    /** @var string Имя поля в REQUEST-запросе */
    protected $requestFieldName = '';

    /**
     * @return string
     */
    public function getRequestFieldName(): string
    {
        return $this->requestFieldName;
    }

    /**
     * @param string $requestFieldName
     * @return static
     */
    public function setRequestFieldName(string $requestFieldName)
    {
        $this->requestFieldName = $requestFieldName;

        return $this;
    }
}
