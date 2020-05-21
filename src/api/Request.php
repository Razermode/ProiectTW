<?php
namespace Api;

use Api\Exception\Unauthorized as UnauthorizedException;
use Api\Model\User as UserModel;

class Request {

    // Hold the class instance.
    private static $instance = null;

    /** @var string */
    private $requestMethod;
    
    /** @var array */
    private $requestURIParts;
    
    /** @var array */
    private $requestParams;
    
    /** @var array */
    private $headers;
    
    /** @var array */
    private $authUser = null;
    
    private function __construct()
    {
        $this->requestMethod = strtoupper(trim($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $this->requestURIParts = explode('/', strtolower(trim($_SERVER['REQUEST_URI'] ?? '/'," /")));
        
        switch ($this->requestMethod) {
            case 'GET':
                $this->requestParams = $_GET;
                break;
            case 'PUT':
                $input = file_get_contents("php://input");
                parse_str($input, $this->requestParams);
                break;
            case 'POST':
                $this->requestParams = $_POST;
                break;
            default:
                $this->requestParams = [];
        }

        $this->headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $this->headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
    }


    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new Request();
        }
        return self::$instance;
    }
    
    /**
     * @return string 
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return array
     */
    public function getRequestURIParts()
    {
        return $this->requestURIParts;
    }
    
    /**
     * @return array
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }

    /**
     * @param string $header
     * @return string|null
     */
    public function getHeader($header)
    {
        return $this->headers[$header] ?? null;
    }
    
    /**
     * @return array
     */
    public function getAuthUser()
    {
        if (is_null($this->authUser)) {
            $authToken = $this->getHeader('Authorization');
            if (!$authToken) {
                throw new UnauthorizedException();
            }
            $this->authUser = UserModel::getInstance()->authorize($authToken);
        }
        return $this->authUser;
    }    
}