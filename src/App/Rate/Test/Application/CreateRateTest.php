<?php
declare(strict_types=1);

namespace CTIC\App\Rate\Test\Application;

use CTIC\App\Rate\Application\CreateRate;
use CTIC\App\Rate\Domain\Command\RateCommand;
use CTIC\App\Rate\Domain\Rate;
use PHPUnit\Framework\TestCase;

final class CreateRateTest extends TestCase
{
    public function testCreateAssert()
    {
        $rateCommandRyu = new RateCommand();
        $rateCommandRyu->name = 'ryu';
        $rateRyu = CreateRate::create($rateCommandRyu);

        $this->assertEquals(Rate::class, get_class($rateRyu));
    }
}