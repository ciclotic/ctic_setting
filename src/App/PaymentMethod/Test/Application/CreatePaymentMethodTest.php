<?php
declare(strict_types=1);

namespace CTIC\App\PaymentMethod\Test\Application;

use CTIC\App\PaymentMethod\Application\CreatePaymentMethod;
use CTIC\App\PaymentMethod\Domain\Command\PaymentMethodCommand;
use CTIC\App\PaymentMethod\Domain\PaymentMethod;
use PHPUnit\Framework\TestCase;

final class CreatePaymentMethodTest extends TestCase
{
    public function testCreateAssert()
    {
        $paymentMethodCommandRyu = new PaymentMethodCommand();
        $paymentMethodCommandRyu->name = 'ryu';
        $paymentMethodRyu = CreatePaymentMethod::create($paymentMethodCommandRyu);

        $this->assertEquals(PaymentMethod::class, get_class($paymentMethodRyu));
    }
}