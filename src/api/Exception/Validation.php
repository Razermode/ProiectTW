<?php
namespace Api\Exception;

use Api\Exception\Exception as ApiException;

class Validation extends ApiException
{
    protected $message = "Validation Error";
    protected $code = 400;
}