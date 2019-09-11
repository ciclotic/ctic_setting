<?php
namespace CTIC\App\PaymentMethod\Domain;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface PaymentMethodInterface extends IdentifiableInterface, EntityInterface
{
    public function getName();
    public function getDescription();
    public function getPrice();
    public function getPercentage();
    public function getCompany();
    public function setCompany(Company $company): bool;
    public function isEnabled();
    public function getExpires();
    public function setExpires($paymentMethod): bool;
}