<?php
namespace Api\Action;

use Api\Request;
use Api\Response;
use Exception;

interface DispatcherInterface {
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function dispatch(Request $request, Response $response):ActionInterface;
}
