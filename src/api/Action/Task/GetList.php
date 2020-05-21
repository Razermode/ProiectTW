<?php
namespace Api\Action\Task;

use Api\Action\TaskAbstract;
use Api\Exception\Exception as ApiException;
use Api\Exception\Unauthorized as UnauthorizedException;

class GetList extends TaskAbstract {
    
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
        $tasks = $this->loadData();
        $data = [];
        foreach($tasks as $task) {
            if ($userId == ($task['user'] ?? -1)) {
                $data[] = $task;
            }
        }
        $this->response
            ->setHttpResponseCode(200)
            ->setData($data)
        ;
    }
}