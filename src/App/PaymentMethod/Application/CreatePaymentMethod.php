<?php
namespace CTIC\App\PaymentMethod\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\PaymentMethod\Domain\Command\PaymentMethodCommand;
use CTIC\App\PaymentMethod\Domain\PaymentMethod;
use Doctrine\Common\Collections\ArrayCollection;

class CreatePaymentMethod implements CreateInterface
{
    /**
     * @param CommandInterface|PaymentMethodCommand $command
     * @return EntityInterface|PaymentMethod
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->setId($command->id);
        $paymentMethod->name = (empty($command->name))? '' : $command->name;
        $paymentMethod->description = (empty($command->description))? '' : $command->description;
        $paymentMethod->enabled = (empty($command->enabled))? false : true;
        $paymentMethod->price = (empty($command->paymentMethod))? 0 : $command->paymentMethod;
        $paymentMethod->percentage = (empty($command->equpaymentMethodlenceProcurement))? 0 : $command->equpaymentMethodlenceProcurement;
        if (!empty($command->expires) && get_class($command->company) == ArrayCollection::class) {
            $paymentMethod->setExpires($command->expires);
        }
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $paymentMethod->setCompany($command->company);
        }

        return $paymentMethod;
    }
}