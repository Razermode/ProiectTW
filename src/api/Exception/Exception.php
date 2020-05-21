<?php
namespace Api\Exception;

class Exception extends \Exception implements ExceptionInterface
{
    protected $httpCode = null;
    protected $message = "System Error";
    protected $code = 500;

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message = null, $code = null)
    {
        if ($message) {
            $this->message = $message;
        }
        if ($code) {
            $this->code = $code;
        }
        parent::__construct($this->message, $this->code);
    }
    
    public function getHttpResponseCode()
    {
        if (!$this->httpCode) {
            if ($this->code >= 200 && $this->code < 600) {
                $this->httpCode = $this->code;
            } else {
                $this->httpCode = 500; 
            }
        }
        return $this->httpCode;
    }
}