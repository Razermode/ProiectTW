<?php
namespace Api\Exception;

use Api\Exception\Exception as ApiException;

class NotFound extends ApiException
{
    protected $message = "Action Not Found";
    protected $code = 404;
}