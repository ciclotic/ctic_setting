<?php
namespace CTIC\App\RealizationArea\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\RealizationArea\Application\CreateRealizationArea;
use CTIC\App\RealizationArea\Domain\Command\RealizationAreaCommand;

class RealizationAreaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $realizationAreaCommandGeneral = new RealizationAreaCommand();
        $realizationAreaCommandGeneral->name = 'General';
        $realizationAreaCommandGeneral->enabled = true;
        $realizationAreaCommandGeneral->company = $companyDefecto;
        $realizationAreaGeneral = CreateRealizationArea::create($realizationAreaCommandGeneral);
        $manager->persist($realizationAreaGeneral);

        $manager->flush();
    }
}