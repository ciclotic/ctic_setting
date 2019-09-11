<?php
namespace CTIC\App\Bank\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface BankInterface extends IdentifiableInterface, EntityInterface
{
    public function getName();
    public function getDescription();
    public function getIban();
    public function getCompany();
    public function setCompany(Company $company): bool;
    public function isEnabled();
}