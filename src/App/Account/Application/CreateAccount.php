<?php
namespace CTIC\App\Account\Application;

use CTIC\App\Account\Domain\Command\AccountCommand;
use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;

class CreateAccount implements CreateInterface
{
    /**
     * @param CommandInterface|AccountCommand $command
     * @return EntityInterface|Account
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $account = new Account();
        $account->name = $command->name;

        return $account;
    }
}