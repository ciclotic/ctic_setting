<?php
namespace CTIC\App\Irpf\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface IrpfInterface extends IdentifiableInterface, EntityInterface
{
    public function getName();
    public function getIrpf();
    public function getCompany();
    public function setCompany(Company $company): bool;
    public function isEnabled();
}