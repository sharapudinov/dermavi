<?php

namespace App\Core\System;

/**
 * Класс для объектов синглтонов
 * Class Singleton
 *
 * @package App\System
 */
abstract class Singleton
{
    /** Трейт, описывающий сигнлтон */
    use SingletonTrait;
}
