<?php
namespace CTIC\App\Company\Domain\Command;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Domain\Command\CommandInterface;

class CompanyCommand implements CommandInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $administratorName;

    /**
     * @var string
     */
    public $taxName;

    /**
     * @var string
     */
    public $businessName;

    /**
     * @var string
     */
    public $administratorIdentification;

    /**
     * @var string
     */
    public $ccc;

    /**
     * @var string
     */
    public $taxIdentification;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $town;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $smtpEmail;

    /**
     * @var string
     */
    public $smtpHost;

    /**
     * @var string
     */
    public $smtpPassword;

    /**
     * @var string
     */
    public $smtpAliasName;

    /**
     * @var bool
     */
    public $includedIVA;

    /**
     * @var bool
     */
    public $defect;

    /**
     * @var bool
     */
    public $enabled;
}