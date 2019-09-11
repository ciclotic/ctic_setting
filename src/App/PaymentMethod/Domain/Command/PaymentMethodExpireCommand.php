<?php
namespace CTIC\App\PaymentMethod\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;
use CTIC\App\PaymentMethod\Domain\PaymentMethod;

class PaymentMethodExpireCommand implements CommandInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $days;

    /**
     * @var float
     */
    public $percentage;

    /**
     * @var PaymentMethod
     */
    public $paymentMethod;
}