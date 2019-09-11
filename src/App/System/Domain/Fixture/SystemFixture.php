<?php
namespace CTIC\App\System\Domain\Fixture;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use CTIC\App\System\Domain\System;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\System\Application\CreateSystem;
use CTIC\App\System\Domain\Command\SystemCommand;

class SystemFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $systemCommand = new SystemCommand();
        $systemCommand->name = 'Default';
        $systemCommand->enabled = true;
        $systemCommand->styleTPV = System::STYLE_TPV_DEFAULT;
        $systemCommand->enabledTPVSound = true;
        $systemCommand->enabledRoundDiscount = true;
        $systemCommand->numberTPVFamilyLines = 5;
        $systemCommand->numberTPVProductLines = 5;
        $systemCommand->enabledTPVAutomaticComplements = false;
        $systemCommand->typeTPVPrint = System::TYPE_TPV_PRINT_PRINT_CASH;
        $systemCommand->typeClientName = System::TYPE_TPV_CLIENT_NAME_BUSINESS;
        $system = CreateSystem::create($systemCommand);
        $manager->persist($system);

        $manager->flush();
    }
}