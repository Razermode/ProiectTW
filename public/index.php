<?php

use Api\Action\FactoryDispatcher;
use Api\Action\TaskDispatcher;
use Api\Action\UserDispatcher;
use Api\Exception\ExceptionInterface as ApiException;
use Api\Exception\NotFound as NotFoundException;
use Api\Request;
use Api\Response;

if (!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['REQUEST_URI'])) {
    exit("Invalid use");
}

define('APP_ROOT', __DIR__);
define('DATA_ROOT', dirname(__DIR__).'/data');

/** Include Autoloader */
include __DIR__ . '/../src/api/autoloader.php';

$requestURI = strtolower(trim($_SERVER['REQUEST_URI']," /"));
$params = explode('/', $requestURI);
try {
    
    $request = Request::getInstance();
    $response = Response::getInstance();
    switch ($params[0]) {
        case 'task':
            $app = new TaskDispatcher();
            break;
        case 'user':
            $app = new UserDispatcher();
            break;
        default:
            throw new NotFoundException("Unknown [{$params[0]}] entity", 404);
    }
    $app->dispatch($request, $response);

    
    $httpResponseCode = $response->getHttpResponseCode();
    $data = $response->getData();
} catch (ApiException $e) {
    $httpResponseCode = $e->getHttpResponseCode();
    $data = [
        'error' => $e->getCode(),
        'message' => $e->getMessage()
    ];
} catch (Exception $e) {
    $httpResponseCode = 500;
    $data = [
        'error' => -1,
        'message' => 'System error (' . $e->getMessage() . ')'
    ];
}

header("Content-type: application/json");
http_response_code($httpResponseCode);
echo json_encode($data, JSON_PRETTY_PRINT),"\n";
