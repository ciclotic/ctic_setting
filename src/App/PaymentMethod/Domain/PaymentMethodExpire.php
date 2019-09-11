<?php
namespace CTIC\App\PaymentMethod\Domain;

use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\PaymentMethod\Domain\Validation\PaymentMethodExpireValidation;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\PaymentMethod\Infrastructure\Repository\PaymentMethodRepository")
 */
class PaymentMethodExpire implements PaymentMethodExpireInterface
{
    use IdentifiableTrait;
    use PaymentMethodExpireValidation;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $days;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $percentage;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="CTIC\App\PaymentMethod\Domain\PaymentMethod", inversedBy="expires")
     * @ORM\JoinColumn(name="paymentmethod_id", referencedColumnName="id")
     *
     */
    public $paymentMethod;

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @return PaymentMethod|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}