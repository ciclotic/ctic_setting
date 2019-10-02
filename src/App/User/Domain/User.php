<?php
namespace CTIC\App\User\Domain;

use CTIC\App\Company\Domain\Company;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\User\Domain\Validation\UserValidation;
use CTIC\App\Account\Domain\Account;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="CTIC\App\User\Infrastructure\Repository\UserRepository")
 */
class User implements UserInterface, SymfonyUserInterface
{
    use IdentifiableTrait;
    use UserValidation;

    const ROLES = array(
        0 => 'ROLE_CUSTOMER',
        1 => 'ROLE_ADMIN',
        2 => 'ROLE_USER',
        3 => 'ROLE_EMPLOYEE'
    );

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     *
     * @var string
     */
    public $username;

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

    public function __construct($username)
    {
        $this->setUsername($username);
    }

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

    // SETTED TO USER SYMFONY INTERFACE

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array((empty($this::ROLES[$this->getPermission()]))? $this::ROLES[0] : $this::ROLES[$this->getPermission()]);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->password = '';
    }
}