<?php
namespace CTIC\App\Bank\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Bank\Domain\Command\BankCommand;
use CTIC\App\Bank\Domain\Bank;

class CreateBank implements CreateInterface
{
    /**
     * @param CommandInterface|BankCommand $command
     * @return EntityInterface|Bank
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $bank = new Bank();
        $bank->setId($command->id);
        $bank->name = (empty($command->name))? '' : $command->name;
        $bank->description = (empty($command->description))? '' : $command->description;
        $bank->iban = (empty($command->iban))? '' : $command->iban;
        $bank->enabled = (empty($command->enabled))? false : true;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $bank->setCompany($command->company);
        }

        return $bank;
    }
}