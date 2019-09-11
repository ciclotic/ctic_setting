<?php
namespace CTIC\App\PaymentMethod\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\PaymentMethod\Domain\Command\PaymentMethodExpireCommand;
use CTIC\App\PaymentMethod\Domain\PaymentMethodExpire;

class CreatePaymentMethodExpire implements CreateInterface
{
    /**
     * @param CommandInterface|PaymentMethodExpireCommand $command
     * @return EntityInterface|PaymentMethodExpire
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $paymentMethodExpire = new PaymentMethodExpire();
        $paymentMethodExpire->setId($command->id);
        $paymentMethodExpire->days = (empty($command->days))? 0 : $command->days;
        $paymentMethodExpire->percentage = (empty($command->percentage))? 0 : $command->percentage;
        $paymentMethodExpire->paymentMethod = $command->paymentMethod;

        return $paymentMethodExpire;
    }
}