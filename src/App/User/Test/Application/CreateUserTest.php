<?php
declare(strict_types=1);

namespace CTIC\App\User\Test\Application;

use CTIC\App\User\Application\CreateUser;
use CTIC\App\User\Domain\Command\UserCommand;
use CTIC\App\User\Domain\User;
use PHPUnit\Framework\TestCase;

final class CreateUserTest extends TestCase
{
    public function testCreateAssert()
    {
        $userCommandRyu = new UserCommand();
        $userCommandRyu->name = 'ryu';
        $userRyu = CreateUser::create($userCommandRyu);

        $this->assertEquals(User::class, get_class($userRyu));
    }
}