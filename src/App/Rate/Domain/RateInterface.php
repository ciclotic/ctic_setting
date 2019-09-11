<?php
namespace CTIC\App\Rate\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface RateInterface extends IdentifiableInterface, EntityInterface
{
    public function getName();
    public function getCompany();
    public function setCompany(Company $company): bool;
    public function isEnabled();
}