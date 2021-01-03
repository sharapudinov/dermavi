<?php

namespace App\Exceptions;

/**
 * Class EmptyOrderCreateException
 * @package App\Exceptions
 */
class EmptyOrderCreateException extends \Exception
{
    protected $message = 'Your cart is empty!';
}
