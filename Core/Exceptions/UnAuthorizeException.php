<?php

namespace App\Core\Exceptions;

class UnAuthorizeException extends \Exception
{
    protected $message = 'Unauthorized';
    protected $code = 401;
}