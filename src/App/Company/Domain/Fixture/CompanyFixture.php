<?php
namespace CTIC\App\Company\Domain\Fixture;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Company\Application\CreateCompany;
use CTIC\App\Company\Domain\Command\CompanyCommand;

class CompanyFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $companyCommand = new CompanyCommand();
        $companyCommand->taxName = 'Defecto';
        $companyCommand->businessName = 'Defecto';
        $companyCommand->administratorName = 'Defecto';
        $companyCommand->administratorIdentification = '1234';
        $companyCommand->taxIdentification = '1234';
        $companyCommand->ccc = '1234';
        $companyCommand->address = 'Introducir';
        $companyCommand->postalCode = '111111';
        $companyCommand->town = 'Introducir';
        $companyCommand->country = 'Introducir';
        $companyCommand->smtpEmail = 'Introducir';
        $companyCommand->smtpHost = 'Introducir';
        $companyCommand->smtpPassword = md5('1234');
        $companyCommand->smtpAliasName = 'Introducir';
        $companyCommand->includedIVA = false;
        $companyCommand->defect = true;
        $companyCommand->enabled = true;
        $company = CreateCompany::create($companyCommand);
        $manager->persist($company);

        $manager->flush();
    }
}