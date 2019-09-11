<?php
namespace CTIC\App\Irpf\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;

class IrpfCommand implements CommandInterface
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
    public $irpf;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $company;
}