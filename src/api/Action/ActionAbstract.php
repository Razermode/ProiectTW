<?php
namespace Api\Action;

use Api\Request;
use Api\Response;

abstract class ActionAbstract implements ActionInterface {
    
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
}
