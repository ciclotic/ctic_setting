<?php
namespace CTIC\App\PaymentMethod\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;
use Doctrine\Common\Collections\ArrayCollection;

class PaymentMethodCommand implements CommandInterface
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
    public $description;

    /**
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $percentage;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var ArrayCollection
     */
    public $expires;
}