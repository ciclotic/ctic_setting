<?php
namespace CTIC\App\PaymentMethod\Domain\Fixture;

use CTIC\App\Company\Infrastructure\Repository\CompanyRepository;
use CTIC\App\Company\Domain\Company;
use CTIC\App\PaymentMethod\Application\CreatePaymentMethodExpire;
use CTIC\App\PaymentMethod\Domain\Command\PaymentMethodExpireCommand;
use CTIC\App\PaymentMethod\Domain\PaymentMethodExpire;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\PaymentMethod\Application\CreatePaymentMethod;
use CTIC\App\PaymentMethod\Domain\Command\PaymentMethodCommand;

class PaymentMethodFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var CompanyRepository $companyRepository
         */
        $companyRepository = $manager->getRepository(Company::class);
        $companyDefecto = $companyRepository->findOneByTaxName('Defecto');

        $paymentMethodCommandGeneral = new PaymentMethodCommand();
        $paymentMethodCommandGeneral->name = 'General';
        $paymentMethodCommandGeneral->description = 'Descripción del método de pago general.';
        $paymentMethodCommandGeneral->enabled = true;
        $paymentMethodCommandGeneral->price = 0;
        $paymentMethodCommandGeneral->percentage = 0;
        $paymentMethodCommandGeneral->company = $companyDefecto;

        $expires = new ArrayCollection();
        $expireCommand = new PaymentMethodExpireCommand();
        $expireCommand->days = 0;
        $expireCommand->percentage = 100;
        $expire = CreatePaymentMethodExpire::create($expireCommand);
        $expires->add($expire);
        $paymentMethodCommandGeneral->expires = $expires;

        $paymentMethodGeneral = CreatePaymentMethod::create($paymentMethodCommandGeneral);
        $manager->persist($paymentMethodGeneral);
        $manager->flush();

        /** @var PaymentMethodExpire $expire*/
        foreach ($expires as $expire) {
            $expire->paymentMethod = $paymentMethodGeneral;
            $manager->persist($expire);
        }
        $manager->flush();
    }
}