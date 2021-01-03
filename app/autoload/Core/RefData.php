<?php

namespace App\Core;

class RefData
{
    private $data;
    private $key;

    public function __construct(string $key, $data)
    {
        $this->key = $key;
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}