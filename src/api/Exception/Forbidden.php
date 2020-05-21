<?php
namespace Api\Exception;

use Api\Exception\Exception as ApiException;

class Forbidden extends ApiException
{
    protected $message = "Forbidden";
    protected $code = 403;
}