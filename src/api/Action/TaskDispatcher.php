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
     * @throws ApiException
     */
    public function dispatch(Request $request, Response $response)
    {
        $requestUriParts = $request->getRequestURIParts();
        $id = $requestUriParts[1] ?? 0;
        $method = $request->getRequestMethod();
        /** @var ActionInterface $actionObj */
        switch ($method) {
            case 'GET':
                if ($id) {
                    $actionObj = new Read($request, $response);
                } else {
                    $actionObj = new GetList($request, $response);
                }
                break;
            case 'POST':
                $actionObj = new Create($request, $response);
                break;
            case 'DELETE':
                $actionObj = new Delete($request, $response);
                break;
            case 'PUT':
                $actionObj = new Update($request, $response);
                break;
            default:
                throw new ApiException("[$method] not implemented");
        }
        $actionObj->execute();
    }
}