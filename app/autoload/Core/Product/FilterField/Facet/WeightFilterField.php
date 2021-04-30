<?php

namespace App\Core\Product\FilterField\Facet;

use App\Core\Product\FilterField\WeightFilter;
use App\Filtration\Dto\FieldDto;
use App\Filtration\Interfaces\FilterFieldInterface;

/**
 * Class WeightFilterField
 *
 * @package App\Core\Product\FilterField\Facet
 */
class WeightFilterField extends AbstractRangeFacetFilterField
{
    /** @var string|FilterFieldInterface */
    protected $filterFieldInstance = WeightFilter::class;

    /** @var string Код идентификации поля */
    protected $filterFieldDtoCode = 'carats';

    /**
     * @param FieldDto $fieldDto
     * @return FieldDto
     */
    public function fieldDtoFinalizer(FieldDto $fieldDto): FieldDto
    {
        $fieldDto = parent::fieldDtoFinalizer($fieldDto);

        // Округление значений на стадии финализации FieldDto
        $this->fieldDtoRoundValues(
            $fieldDto,
            function ($value) {
                return round((float)$value, 2);
            }
        );

        return $fieldDto;
    }
}
