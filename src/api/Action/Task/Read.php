<?php
namespace Api\Action\Task;

use Api\Action\Task\Task as TaskAction;
use Api\Exception\Exception as ApiException;
use Api\Exception\NotFound as NotFoundException;

class Read extends TaskAction {
    
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
        $data = $this->loadData($id);

        $this->response
            ->setHttpResponseCode(200)
            ->setData($data)
        ;
    }
}