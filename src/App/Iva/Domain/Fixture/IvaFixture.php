<?php
namespace CTIC\App\Iva\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Iva\Application\CreateIva;
use CTIC\App\Iva\Domain\Command\IvaCommand;

class IvaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $ivaCommandGeneral = new IvaCommand();
        $ivaCommandGeneral->name = 'General';
        $ivaCommandGeneral->enabled = true;
        $ivaCommandGeneral->iva = 21;
        $ivaCommandGeneral->equivalenceProcurement = 5.2;
        $ivaCommandGeneral->company = $companyDefecto;
        $ivaGeneral = CreateIva::create($ivaCommandGeneral);
        $manager->persist($ivaGeneral);

        $ivaCommandGeneral = new IvaCommand();
        $ivaCommandGeneral->name = 'Reducido';
        $ivaCommandGeneral->enabled = true;
        $ivaCommandGeneral->iva = 10;
        $ivaCommandGeneral->equivalenceProcurement = 1.4;
        $ivaCommandGeneral->company = $companyDefecto;
        $ivaGeneral = CreateIva::create($ivaCommandGeneral);
        $manager->persist($ivaGeneral);

        $ivaCommandGeneral = new IvaCommand();
        $ivaCommandGeneral->name = 'Super Reducido';
        $ivaCommandGeneral->enabled = true;
        $ivaCommandGeneral->iva = 4;
        $ivaCommandGeneral->equivalenceProcurement = 0.5;
        $ivaCommandGeneral->company = $companyDefecto;
        $ivaGeneral = CreateIva::create($ivaCommandGeneral);
        $manager->persist($ivaGeneral);

        $manager->flush();
    }
}