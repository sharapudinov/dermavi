<?php

namespace App\Core\Traits;

use App\Core\RefData;

trait StoredRefTrait
{
    private $dataRefStore;
    private static $dataRef;

    abstract protected function getRefStore(): string;

    private function getRefData(string $refName, string $key, callable $data)
    {

        $storeName = $this->getRefStore();
        $store = static::$dataRef[$storeName];
        $ref = $store[$refName][$key];
        if ($ref) {
            return $ref->getData();
        }

        static::$dataRef[$storeName][$refName][$key] = new RefData($key, $data());
        return static::$dataRef[$storeName][$refName][$key]->getData();
    }
}
