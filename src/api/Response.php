<?php
namespace Api;

class Response {

    // Hold the class instance.
    private static $instance = null;
    
    private $data = [];
    private $httpResponseCode = 200;

    
    private function __construct()
    {}


    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new Response();
        }
        return self::$instance;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function setHttpResponseCode($code) {
        $this->httpResponseCode = $code;
        return $this;
    }
    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode() {
        return $this->httpResponseCode;
    }
}