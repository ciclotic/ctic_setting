<?php
namespace CTIC\App\Origin\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;

class OriginCommand implements CommandInterface
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
    public $origin;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $company;
}