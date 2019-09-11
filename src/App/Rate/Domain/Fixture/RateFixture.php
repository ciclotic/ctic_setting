<?php
namespace CTIC\App\Rate\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Rate\Application\CreateRate;
use CTIC\App\Rate\Domain\Command\RateCommand;

class RateFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $rateCommandGeneral = new RateCommand();
        $rateCommandGeneral->name = 'General';
        $rateCommandGeneral->enabled = true;
        $rateCommandGeneral->company = $companyDefecto;
        $rateGeneral = CreateRate::create($rateCommandGeneral);
        $manager->persist($rateGeneral);

        $manager->flush();
    }
}