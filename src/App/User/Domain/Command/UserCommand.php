<?php
namespace CTIC\App\User\Domain\Command;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;

class UserCommand implements CommandInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var int
     */
    public $permission;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $defaultCompany;

    /**
     * @var Account
     */
    public $account;
}