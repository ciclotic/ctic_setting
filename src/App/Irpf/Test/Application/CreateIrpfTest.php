<?php
declare(strict_types=1);

namespace CTIC\App\Irpf\Test\Application;

use CTIC\App\Irpf\Application\CreateIrpf;
use CTIC\App\Irpf\Domain\Command\IrpfCommand;
use CTIC\App\Irpf\Domain\Irpf;
use PHPUnit\Framework\TestCase;

final class CreateIrpfTest extends TestCase
{
    public function testCreateAssert()
    {
        $irpfCommandRyu = new IrpfCommand();
        $irpfCommandRyu->name = 'ryu';
        $irpfRyu = CreateIrpf::create($irpfCommandRyu);

        $this->assertEquals(Irpf::class, get_class($irpfRyu));
    }
}