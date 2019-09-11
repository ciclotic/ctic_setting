<?php
declare(strict_types=1);

namespace CTIC\App\Iva\Test\Application;

use CTIC\App\Iva\Application\CreateIva;
use CTIC\App\Iva\Domain\Command\IvaCommand;
use CTIC\App\Iva\Domain\Iva;
use PHPUnit\Framework\TestCase;

final class CreateIvaTest extends TestCase
{
    public function testCreateAssert()
    {
        $ivaCommandRyu = new IvaCommand();
        $ivaCommandRyu->name = 'ryu';
        $ivaRyu = CreateIva::create($ivaCommandRyu);

        $this->assertEquals(Iva::class, get_class($ivaRyu));
    }
}