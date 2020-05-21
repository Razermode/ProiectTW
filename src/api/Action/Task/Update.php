<?php
namespace Api\Action\Task;

use Api\Action\TaskAbstract;
use Api\Exception\Exception as ApiException;
use Api\Exception\Forbidden;
use Api\Exception\NotFound as NotFoundException;
use Api\Exception\Unauthorized as UnauthorizedException;
use Api\Exception\Validation;

class Update extends TaskAbstract
{
    /**
     * @throws ApiException
     */
    public function execute()
    {
        $authUser = $this->request->getAuthUser();
        $userId = $authUser['id'] ?? 0;
        if (!$userId) {
            throw new UnauthorizedException();
        }
        $requestUriParts = $this->request->getRequestURIParts();
        $id = $requestUriParts[1] ?? 0;
        if (!$id) {
            throw new NotFoundException();
        }
        $tasks =  $this->loadData();
        $data = $tasks[$id] ?? null;
        if (!$data) {
            throw new NotFoundException();
        }
        if ($data['user'] != $userId) {
            throw new NotFoundException();
        }
        
        $params = $this->request->getRequestParams();
        
        foreach($params as $k=>$v) {
            if ($k== 'id' || $k == 'user') {
                throw new Forbidden();
            }
            if (!isset($data[$k])) {
                throw new Validation();
            }
            
            $data['_originalData'][$k] = $data[$k];
            $data[$k] = $v;
            $tasks[$id][$k] = $v;
        }
        $this->saveData($tasks);
        
        $this->response
            ->setHttpResponseCode(200)
            ->setData($data)
        ;
    }
}