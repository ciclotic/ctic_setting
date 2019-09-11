<?php
namespace CTIC\App\Company\Domain;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Company\Domain\Validation\CompanyValidation;
use CTIC\Warehouse\Warehouse\Domain\Warehouse;
use CTIC\App\Account\Domain\Account;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="CTIC\App\Company\Infrastructure\Repository\CompanyRepository")
 */
class Company implements CompanyInterface
{
    use IdentifiableTrait;
    use CompanyValidation;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $administratorName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $taxName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $businessName;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $administratorIdentification;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @var string
     */
    public $taxIdentification;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $ccc;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $address;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @var string
     */
    public $postalCode;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $town;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $country;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $smtpEmail;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $smtpHost;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $smtpPassword;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    public $smtpAliasName;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $includedIVA;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $defect;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabled;

    /**
     * @ORM\OneToMany(targetEntity="CTIC\Warehouse\Warehouse\Domain\Warehouse", mappedBy="company", cascade={"persist"})
     *
     * @var Warehouse[]
     */
    public $warehouses;

    /**
     * @return string
     */
    public function getTaxName()
    {
        return $this->taxName;
    }

    /**
     * @return string
     */
    public function getAdministratorName()
    {
        return $this->administratorName;
    }

    /**
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @return string
     */
    public function getTaxIdentification()
    {
        return $this->taxIdentification;
    }

    /**
     * @return string
     */
    public function getAdministratorIdentification()
    {
        return $this->administratorIdentification;
    }

    /**
     * @return string
     */
    public function getCCC()
    {
        return $this->ccc;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getSmtpEmail()
    {
        return $this->smtpEmail;
    }

    /**
     * @return string
     */
    public function getSmtpHost()
    {
        return $this->smtpHost;
    }

    /**
     * @return string
     */
    public function getSmtpPassword()
    {
        return (empty($this->smtpPassword))? '' : $this->smtpPassword;
    }

    /**
     * @return string
     */
    public function getSmtpAliasName()
    {
        return $this->smtpAliasName;
    }

    /**
     * @return bool
     */
    public function isIncludedIVA()
    {
        return $this->includedIVA;
    }

    /**
     * @return bool
     */
    public function isDefect()
    {
        return $this->defect;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}