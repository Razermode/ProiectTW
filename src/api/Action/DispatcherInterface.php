<?php
namespace Api\Action;

use Api\Request;
use Api\Response;
use Exception;

interface DispatcherInterface {
    /**
     * @throws Exception
     */
    public function dispatch(Request $request, Response $response);
}
