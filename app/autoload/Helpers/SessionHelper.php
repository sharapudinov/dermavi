<?php

namespace App\Helpers;

class SessionHelper
{
    const CONSTRUCTOR_STEP = 'constructor_step';

    public static function setValue($name, $value)
    {
        session_start();
        $_SESSION[$name] = $value;
    }

    public static function getValue($name)
    {
        session_start();
        return $_SESSION[$name];
    }
}