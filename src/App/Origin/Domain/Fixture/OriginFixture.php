<?php
namespace CTIC\App\Origin\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Origin\Application\CreateOrigin;
use CTIC\App\Origin\Domain\Command\OriginCommand;

class OriginFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $originCommandGeneral = new OriginCommand();
        $originCommandGeneral->name = 'General';
        $originCommandGeneral->enabled = true;
        $originCommandGeneral->company = $companyDefecto;
        $originGeneral = CreateOrigin::create($originCommandGeneral);
        $manager->persist($originGeneral);

        $manager->flush();
    }
}