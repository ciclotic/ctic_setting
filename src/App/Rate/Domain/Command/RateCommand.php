<?php
namespace CTIC\App\Rate\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;

class RateCommand implements CommandInterface
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
     * @var int
     */
    public $rate;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $company;
}