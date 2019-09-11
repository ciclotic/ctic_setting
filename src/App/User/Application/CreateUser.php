<?php
namespace CTIC\App\User\Application;

use CTIC\App\Account\Application\CreateAccount;
use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Domain\Command\AccountCommand;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Company\Domain\Company;
use CTIC\App\User\Domain\Command\UserCommand;
use CTIC\App\User\Domain\User;

class CreateUser implements CreateInterface
{
    /**
     * @param CommandInterface|UserCommand $command
     * @return EntityInterface|User
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $user = new User();
        $user->setId($command->id);
        $user->name = (empty($command->name))? '' : $command->name;
        $user->enabled = (empty($command->enabled))? false : true;
        $user->password = md5($command->password);
        $user->permission = (empty($command->permission))? 3 : $command->permission;
        if (!empty($command->defaultCompany) && get_class($command->defaultCompany) == Company::class) {
            $user->setDefaultCompany($command->defaultCompany);
        }
        if (!empty($command->account) && get_class($command->account) == Account::class) {
            $user->setAccount($command->account);
        }

        return $user;
    }
}