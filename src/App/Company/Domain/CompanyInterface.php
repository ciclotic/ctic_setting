<?php
namespace CTIC\App\Company\Domain;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface CompanyInterface extends IdentifiableInterface, EntityInterface
{
    public function getAdministratorName();
    public function getTaxName();
    public function getBusinessName();
    public function getTaxIdentification();
    public function getAdministratorIdentification();
    public function getCCC();
    public function getAddress();
    public function getPostalCode();
    public function getTown();
    public function getCountry();
    public function getSmtpEmail();
    public function getSmtpHost();
    public function getSmtpPassword();
    public function getSmtpAliasName();
    public function isIncludedIVA();
    public function isDefect();
    public function isEnabled();
}