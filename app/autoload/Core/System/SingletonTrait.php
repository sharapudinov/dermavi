<?php

namespace App\Core\System;

/**
 * Трейт, описывающий синглтон
 * Trait SingletonTrait
 *
 * @package App\Core\System
 */
trait SingletonTrait
{
    /**
     * SingletonTrait constructor.
     */
    protected function __construct()
    {
    }

    /**
     * SingletonTrait cloner
     */
    private function __clone()
    {
    }

    /**
     * @return static
     */
    final public static function getInstance()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new static();
        }

        return $instance;
    }
}
