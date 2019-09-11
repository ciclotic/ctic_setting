<?php
declare(strict_types=1);

namespace CTIC\App\System\Test\Application;

use CTIC\App\System\Application\CreateSystem;
use CTIC\App\System\Domain\Command\SystemCommand;
use CTIC\App\System\Domain\System;
use PHPUnit\Framework\TestCase;

final class CreateSystemTest extends TestCase
{
    public function testCreateAssert()
    {
        $systemCommandRyu = new SystemCommand();
        $systemCommandRyu->name = 'ryu';
        $systemRyu = CreateSystem::create($systemCommandRyu);

        $this->assertEquals(System::class, get_class($systemRyu));
    }
}