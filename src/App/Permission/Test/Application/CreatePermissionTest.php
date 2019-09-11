<?php
declare(strict_types=1);

namespace CTIC\App\Permission\Test\Application;

use CTIC\App\Permission\Application\CreatePermission;
use CTIC\App\Permission\Domain\Command\PermissionCommand;
use CTIC\App\Permission\Domain\Permission;
use PHPUnit\Framework\TestCase;

final class CreatePermissionTest extends TestCase
{
    public function testCreateAssert()
    {
        $permissionCommand = new PermissionCommand();
        $permissionCommand->root = true;
        $permission = CreatePermission::create($permissionCommand);

        $this->assertEquals(Permission::class, get_class($permission));
    }
}