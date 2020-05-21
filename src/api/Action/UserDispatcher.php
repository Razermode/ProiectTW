<?php
namespace Api\Action;

use Api\Action\User\Login;
use Api\Action\User\Register;
use Api\Exception\Exception as ApiException;
use Api\Request;
use Api\Response;

class UserDispatcher implements DispatcherInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @throws ApiException
     */
    public function dispatch(Request $request, Response $response) 
    {
        $method = $request->getRequestMethod();
        /** @var ActionInterface $actionObj */
        switch ($method) {
            case 'POST':
                $requestUriParts = $request->getRequestURIParts();
                $action = $requestUriParts[1] ?? '-';
                switch ($action) {
                    case 'login':
                        $actionObj = new Login($request, $response);
                        break;
                    case 'register':
                        $actionObj = new Register($request, $response);
                        break;
                    default:
                        throw new ApiException("[$action] action not implemented");
                }
                break;
            default:
                throw new ApiException("[$method] method not implemented");
        }
        $actionObj->execute();
    }
}