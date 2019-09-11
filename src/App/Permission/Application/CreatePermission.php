<?php
namespace CTIC\App\Permission\Application;

use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Permission\Domain\Command\PermissionCommand;
use CTIC\App\Permission\Domain\Permission;

class CreatePermission implements CreateInterface
{
    /**
     * @param CommandInterface|PermissionCommand $command
     * @return EntityInterface|Permission
     *
     * @throws \Exception
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $permission = new Permission();
        $permission->route = $command->route;
        if ($permission->setType($command->type) === false) {
            throw new \Exception('Permission type not available.');
        }


        return $permission;
    }
}