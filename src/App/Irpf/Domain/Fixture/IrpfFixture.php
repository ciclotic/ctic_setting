<?php
namespace CTIC\App\Irpf\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Irpf\Application\CreateIrpf;
use CTIC\App\Irpf\Domain\Command\IrpfCommand;

class IrpfFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $irpfCommandGeneral = new IrpfCommand();
        $irpfCommandGeneral->name = 'General';
        $irpfCommandGeneral->enabled = true;
        $irpfCommandGeneral->irpf = 20;
        $irpfCommandGeneral->company = $companyDefecto;
        $irpfGeneral = CreateIrpf::create($irpfCommandGeneral);
        $manager->persist($irpfGeneral);

        $manager->flush();
    }
}