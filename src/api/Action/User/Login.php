<?php
namespace Api\Action\User;

use Api\Action\UserAbstract;
use Api\Exception\Exception as ApiException;
use Api\Exception\Validation as ValidationException;

class Login extends UserAbstract {
    
    /**
     * @throws ApiException
     *
     */
    public function execute()
    {
        $params = $this->request->getRequestParams();
        $email = trim((string)($params['email'] ?? ''));
        $pass =  trim((string)($params['password'] ?? ''));
        if (!$email || !$pass) {
            throw new ValidationException("Invalid credentials");
        }
        $users = $this->loadData();
        $return = [];

        foreach ($users as $id=>$data) {
            if ($email == $data['email']) {
                if ($pass == $data['password']) {
                    $return = [
                        'auth' => $data['auth'],
                        'name' => $data['name']
                    ];
                }
                break;
            }
        }
        if (!$return) {
            throw new ValidationException("Invalid email and/or password!");
        }
        $this->response
            ->setHttpResponseCode(200)
            ->setData($return)
        ;
    }
}