<?php
declare(strict_types=1);

namespace CTIC\App\User\Test\Application;

use CTIC\App\Account\Application\CreateAccount;
use CTIC\App\Account\Domain\Command\AccountCommand;
use CTIC\App\User\Domain\User;
use PHPUnit\Framework\TestCase;

final class CreateAccountTest extends TestCase
{
    public function testCreateAssert()
    {
        $accountCommandCTIC = new AccountCommand();
        $accountCommandCTIC->name = 'CTIC';
        $accountCTIC = CreateAccount::create($accountCommandCTIC);

        $this->assertEquals(User::class, get_class($accountCTIC));
    }
}