<?php
namespace Api\Model;

use Api\Exception\Exception as ApiException;
use Api\Exception\Unauthorized as UnauthorizedException;

class User extends Model
{
    protected $dataFile = 'users.json';

    /**
     * @param $authToken
     * @return array
     * @throws UnauthorizedException
     * @throws ApiException
     */
    public function authorize($authToken)
    {
        $users = $this->loadData();
        foreach ($users as $user) {
            if ($authToken == $user['auth']) {
                return $user;
            }
        }
        throw new UnauthorizedException();
    }
}
