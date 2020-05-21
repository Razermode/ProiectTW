<?php
namespace Api\Action\Task;

use Api\Action\Task\Task as TaskAction;
use Api\Exception\Exception as ApiException;
use Api\Exception\Unauthorized as UnauthorizedException;

class Create extends TaskAction
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
        $params = $this->request->getRequestParams();
        $tasks =  $this->loadData();
        $id = max(array_keys($tasks)) + 1;
        $data = [
            'id' => $id,
            'title' => $params['title'] ?? 'no title',
            'date' => $params['date'] ?? date('Y-m-d'),
            'status' => 'pending', 
            'description' => $params['description'] ?? 'no description',
            'user' => $userId
        ];
        $tasks[$id] = $data;
        $this->saveData($tasks);
        $this->response
            ->setHttpResponseCode(201)
            ->setData($data)
        ;
    }
}