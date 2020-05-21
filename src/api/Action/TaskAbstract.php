<?php
namespace Api\Action;

use Api\Exception\Exception as ApiException;
use Api\Model\Task as TaskModel;

abstract class TaskAbstract extends ActionAbstract
{
    /**
     * @param int $id
     * @return array
     * @throws ApiException
     */
    protected function loadData($id = null)
    {
        return TaskModel::getInstance()->loadData($id);
    }
    
    /**
     * @param array $data
     * @throws ApiException
     */
    protected function saveData($data)
    {
        TaskModel::getInstance()->saveData($data);
    }
}