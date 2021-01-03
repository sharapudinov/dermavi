<?php

namespace App\Filtration\Interfaces;

use App\Filtration\DataProvider\Result\FilterDataResult;
use Generator;
use Throwable;

/**
 * Interface FilterDataProviderInterface
 *
 * @package App\Filtration\Interfaces
 */
interface FilterDataProviderInterface
{
    /**
     * @param FilterRequestInterface|null $filterRequest
     * @return Generator|FilterDataResult[]|null
     * @throws Throwable
     */
    public function getFilterDataResult(FilterRequestInterface $filterRequest = null): ?Generator;
}
