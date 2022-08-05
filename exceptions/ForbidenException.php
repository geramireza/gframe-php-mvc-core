<?php

namespace App\core\exceptions;

use Exception;

class ForbidenException extends Exception
{
    protected $code = 403;
    protected $message = "You don't have permissions to access this page";

}