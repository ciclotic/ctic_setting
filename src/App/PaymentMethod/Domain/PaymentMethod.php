<?php
namespace CTIC\App\PaymentMethod\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\PaymentMethod\Domain\Validation\PaymentMethodValidation;
use CTIC\App\Company\Domain\Company;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\PaymentMethod\Infrastructure\Repository\PaymentMethodRepository")
 */
class PaymentMethod implements PaymentMethodInterface
{
    use IdentifiableTrait;
    use PaymentMethodValidation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    public $description;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $price;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $percentage;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="CTIC\App\Company\Domain\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     *
     * @var Company
     */
    private $company = null;

    /**
     * @ORM\OneToMany(targetEntity="CTIC\App\PaymentMethod\Domain\PaymentMethodExpire", mappedBy="paymentMethod", cascade={"persist", "remove"})
     *
     * @var PaymentMethodExpire[]
     */
    private $expires;

    /**
     * PaymentMethod constructor.
     */
    public function __construct()
    {
        $this->expires = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @return Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     *
     * @return bool
     */
    public function setCompany(Company $company): bool
    {
        if (get_class($company) != Company::class) {
            return false;
        }

        $this->company = $company;

        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return PaymentMethodExpire[]|ArrayCollection
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param $paymentMethod
     * @return bool
     */
    public function setExpires($paymentMethod): bool
    {
        if (get_class($paymentMethod) != ArrayCollection::class) {
            return false;
        }

        $this->expires = $paymentMethod;

        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}