<?php
namespace Api\Action;

use Api\Exception\NotFound as NotFoundException;

class FactoryDispatcher {

    /**
     * @param string $entity
     * @return DispatcherInterface
     * @throws NotFoundException
     */
    public static function select(string $entity) : DispatcherInterface
    {
        switch ($entity) {
            case 'task':
                $app = new TaskDispatcher();
                break;
            case 'user':
                $app = new UserDispatcher();
                break;
            default:
                throw new NotFoundException("Unknown [{$entity}] entity", 404);
        }
        return $app;
    }
}
