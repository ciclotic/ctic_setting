<?php
namespace CTIC\App\User\Domain;

use CTIC\App\Company\Domain\Company;
use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\User\Domain\Validation\UserValidation;
use CTIC\App\Account\Domain\Account;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\User\Infrastructure\Repository\UserRepository")
 */
class User implements UserInterface
{
    use IdentifiableTrait;
    use UserValidation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, options={"default" : "nc"})
     *
     * @var string
     */
    public $password;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabled;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 3})
     *
     * @var int
     */
    public $permission;

    /**
     * @ORM\ManyToOne(targetEntity="CTIC\App\Company\Domain\Company")
     * @ORM\JoinColumn(name="default_company_id", referencedColumnName="id")
     *
     * @var Company
     */
    private $defaultCompany = null;

    /**
     * @ORM\ManyToOne(targetEntity="CTIC\App\Account\Domain\Account")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     *
     * @var Account
     */
    private $account = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return (empty($this->password))? '' : $this->password;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return bool
     */
    public function setAccount(Account $account): bool
    {
        if (get_class($account) != Account::class) {
            return false;
        }

        $this->account = $account;

        return true;
    }

    /**
     * @return Company|null
     */
    public function getDefaultCompany()
    {
        return $this->defaultCompany;
    }

    /**
     * @param Company $company
     *
     * @return bool
     */
    public function setDefaultCompany(Company $company): bool
    {
        if (get_class($company) != Company::class) {
            return false;
        }

        $this->defaultCompany = $company;

        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}