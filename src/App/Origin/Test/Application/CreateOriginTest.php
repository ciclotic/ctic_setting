<?php
declare(strict_types=1);

namespace CTIC\App\Origin\Test\Application;

use CTIC\App\Origin\Application\CreateOrigin;
use CTIC\App\Origin\Domain\Command\OriginCommand;
use CTIC\App\Origin\Domain\Origin;
use PHPUnit\Framework\TestCase;

final class CreateOriginTest extends TestCase
{
    public function testCreateAssert()
    {
        $originCommandRyu = new OriginCommand();
        $originCommandRyu->name = 'ryu';
        $originRyu = CreateOrigin::create($originCommandRyu);

        $this->assertEquals(Origin::class, get_class($originRyu));
    }
}