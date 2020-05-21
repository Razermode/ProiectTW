<?php
namespace Api\Action\User;

use Api\Action\ActionAbstract;
use Api\Exception\Exception as ApiException;
use Api\Model\User as UserModel;

abstract class User extends ActionAbstract
{
    /**
     * @param int $id
     * @return array
     * @throws ApiException
     */
    protected function loadData($id = null)
    {
        return UserModel::getInstance()->loadData($id);
    }
    
    /**
     * @param array $data
     * @throws ApiException
     */
    protected function saveData($data)
    {
        UserModel::getInstance()->saveData($data);
    }
}