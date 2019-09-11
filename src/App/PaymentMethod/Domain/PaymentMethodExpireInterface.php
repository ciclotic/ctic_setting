<?php
namespace CTIC\App\PaymentMethod\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface PaymentMethodExpireInterface extends IdentifiableInterface, EntityInterface
{
    public function getDays();
    public function getPercentage();
    public function getPaymentMethod();
}