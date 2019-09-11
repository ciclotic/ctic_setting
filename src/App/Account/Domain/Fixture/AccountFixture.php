<?php
namespace CTIC\App\Account\Domain\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Account\Application\CreateAccount;
use CTIC\App\Account\Domain\Command\AccountCommand;

class AccountFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $accountCommandRenault = new AccountCommand();
        $accountCommandRenault->name = 'cicloTIC';
        $accountRenault = CreateAccount::create($accountCommandRenault);
        $manager->persist($accountRenault);

        $manager->flush();
    }
}