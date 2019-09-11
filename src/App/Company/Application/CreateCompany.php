<?php
namespace CTIC\App\Company\Application;

use CTIC\App\Account\Application\CreateAccount;
use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Domain\Command\AccountCommand;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Company\Domain\Command\CompanyCommand;
use CTIC\App\Company\Domain\Company;

class CreateCompany implements CreateInterface
{
    /**
     * @param CommandInterface|CompanyCommand $command
     * @return EntityInterface|Company
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $company = new Company();
        $company->setId($command->id);
        $company->administratorName = $command->administratorName;
        $company->taxName = $command->taxName;
        $company->businessName = $command->businessName;
        $company->administratorIdentification = $command->administratorIdentification;
        $company->taxIdentification = $command->taxIdentification;
        $company->ccc = $command->ccc;
        $company->address = $command->address;
        $company->postalCode = $command->postalCode;
        $company->town = $command->town;
        $company->country = $command->country;
        $company->smtpEmail = $command->smtpEmail;
        $company->smtpHost = $command->smtpHost;
        $company->smtpPassword = $command->smtpPassword;
        $company->smtpAliasName = $command->smtpAliasName;
        $company->includedIVA = $command->includedIVA;
        $company->defect = $command->defect;
        $company->enabled = $command->enabled;

        return $company;
    }
}