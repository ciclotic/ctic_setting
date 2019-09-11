<?php
namespace CTIC\App\User\Domain;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;
use CTIC\App\Company\Domain\Company;

interface UserInterface extends IdentifiableInterface, EntityInterface
{
    public function getName(): string;
    public function getPassword(): string;

    public function getAccount();
    public function setAccount(Account $account): bool;

    public function getDefaultCompany();
    public function setDefaultCompany(Company $company): bool;

    public function getPermission();

    public function isEnabled(): bool;
}