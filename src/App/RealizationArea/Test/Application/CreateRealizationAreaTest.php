<?php
declare(strict_types=1);

namespace CTIC\App\RealizationArea\Test\Application;

use CTIC\App\RealizationArea\Application\CreateRealizationArea;
use CTIC\App\RealizationArea\Domain\Command\RealizationAreaCommand;
use CTIC\App\RealizationArea\Domain\RealizationArea;
use PHPUnit\Framework\TestCase;

final class CreateRealizationAreaTest extends TestCase
{
    public function testCreateAssert()
    {
        $realizationAreaCommandRyu = new RealizationAreaCommand();
        $realizationAreaCommandRyu->name = 'ryu';
        $realizationAreaRyu = CreateRealizationArea::create($realizationAreaCommandRyu);

        $this->assertEquals(RealizationArea::class, get_class($realizationAreaRyu));
    }
}