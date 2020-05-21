<?php
namespace Api\Action;

use Api\Action\Task\Create;
use Api\Action\Task\Delete;
use Api\Action\Task\GetList;
use Api\Action\Task\Read;
use Api\Action\Task\Update;
use Api\Exception\Exception as ApiException;
use Api\Request;
use Api\Response;

class TaskDispatcher implements DispatcherInterface {

    /**
     * @param Request $request
     * @param Response $response
     * @return ActionInterface
     * @throws ApiException
     */
    public function dispatch(Request $request, Response $response) : ActionInterface
    {
        $requestUriParts = $request->getRequestURIParts();
        $id = $requestUriParts[1] ?? 0;
        $method = $request->getRequestMethod();
        $action = null;

        switch ($method) {
            case 'GET':
                if ($id) {
                    $action = new Read($request, $response);
                } else {
                    $action = new GetList($request, $response);
                }
                break;
            case 'POST':
                $action = new Create($request, $response);
                break;
            case 'DELETE':
                $action = new Delete($request, $response);
                break;
            case 'PUT':
                $action = new Update($request, $response);
                break;
            default:
                throw new ApiException("[$method] not implemented");
        }
        return $action;
    }
}