<?php
namespace Api\Action\User;

use Api\Action\UserAbstract;

class Register extends UserAbstract
{
    public function execute() {
        $params = $this->request->getRequestParams();
        $this->response
            ->setHttpResponseCode(501) //501 Not Implemented
            ->setData($params)
        ;
    }
}
