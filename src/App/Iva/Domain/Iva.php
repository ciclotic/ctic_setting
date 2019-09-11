<?php
namespace CTIC\App\Iva\Domain;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Iva\Domain\Validation\IvaValidation;
use CTIC\App\Company\Domain\Company;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="CTIC\App\Iva\Infrastructure\Repository\IvaRepository")
 */
class Iva implements IvaInterface
{
    use IdentifiableTrait;
    use IvaValidation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $iva;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $equivalenceProcurement;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * @return int
     */
    public function getEquivalenceProcurement()
    {
        return $this->equivalenceProcurement;
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}