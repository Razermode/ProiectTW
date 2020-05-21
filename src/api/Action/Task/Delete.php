<?php
namespace Api\Action\Task;

use Api\Action\TaskAbstract;
use Api\Exception\Exception as ApiException;
use Api\Exception\NotFound as NotFoundException;

class Delete extends TaskAbstract
{
    /**
     * @throws ApiException
     */
    public function execute()
    {
        $requestUriParts = $this->request->getRequestURIParts();
        $id = $requestUriParts[1] ?? 0;
        if (!$id) {
            throw new NotFoundException();
        }
        
        $data = $this->loadData();
        if (!isset($data[$id])) {
            throw new NotFoundException("Task not found");
        }
        $oldData = $data[$id];
        unset($data[$id]);
        $this->saveData($data);
        $data = [
            'message' => 'OK',
            '_originalData' => $oldData            
        ];
        $this->response
            ->setHttpResponseCode(200)
            ->setData($data)
        ;
    }
}