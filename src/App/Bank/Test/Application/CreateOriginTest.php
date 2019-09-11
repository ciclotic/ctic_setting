<?php
declare(strict_types=1);

namespace CTIC\App\Bank\Test\Application;

use CTIC\App\Bank\Application\CreateBank;
use CTIC\App\Bank\Domain\Command\BankCommand;
use CTIC\App\Bank\Domain\Bank;
use PHPUnit\Framework\TestCase;

final class CreateBankTest extends TestCase
{
    public function testCreateAssert()
    {
        $bankCommandRyu = new BankCommand();
        $bankCommandRyu->name = 'ryu';
        $bankRyu = CreateBank::create($bankCommandRyu);

        $this->assertEquals(Bank::class, get_class($bankRyu));
    }
}