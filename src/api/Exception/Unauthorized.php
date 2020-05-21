<?php
namespace Api\Exception;

use Api\Exception\Exception as ApiException;

class Unauthorized extends ApiException
{
    protected $message = "Unauthorized";
    protected $code = 401;
}