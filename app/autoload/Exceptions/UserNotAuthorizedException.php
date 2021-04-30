<?php


namespace App\Exceptions;


class UserNotAuthorizedException extends \Exception
{
    protected $message = 'User is not authorized';
}
