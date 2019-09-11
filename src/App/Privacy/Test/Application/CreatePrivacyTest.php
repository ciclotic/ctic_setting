<?php
declare(strict_types=1);

namespace CTIC\App\Privacy\Test\Application;

use CTIC\App\Privacy\Application\CreatePrivacy;
use CTIC\App\Privacy\Domain\Command\PrivacyCommand;
use CTIC\App\Privacy\Domain\Privacy;
use PHPUnit\Framework\TestCase;

final class CreatePrivacyTest extends TestCase
{
    public function testCreateAssert()
    {
        $privacyCommand = new PrivacyCommand();
        $privacyCommand->root = true;
        $privacy = CreatePrivacy::create($privacyCommand);

        $this->assertEquals(Privacy::class, get_class($privacy));
    }
}