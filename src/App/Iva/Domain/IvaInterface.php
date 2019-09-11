<?php
namespace CTIC\App\Iva\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface IvaInterface extends IdentifiableInterface, EntityInterface
{
    public function getName();
    public function getIva();
    public function getEquivalenceProcurement();
    public function getCompany();
    public function setCompany(Company $company): bool;
    public function isEnabled();
}