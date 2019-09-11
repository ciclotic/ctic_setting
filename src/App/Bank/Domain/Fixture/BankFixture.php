<?php
namespace CTIC\App\Bank\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Bank\Application\CreateBank;
use CTIC\App\Bank\Domain\Command\BankCommand;

class BankFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $bankCommandGeneral = new BankCommand();
        $bankCommandGeneral->name = 'General';
        $bankCommandGeneral->description = 'Descripción general';
        $bankCommandGeneral->iban = 'ES68 0081 0049 5300 0236 4646';
        $bankCommandGeneral->enabled = true;
        $bankCommandGeneral->company = $companyDefecto;
        $bankGeneral = CreateBank::create($bankCommandGeneral);
        $manager->persist($bankGeneral);

        $manager->flush();
    }
}