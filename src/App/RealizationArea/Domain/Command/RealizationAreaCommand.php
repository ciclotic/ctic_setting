<?php
namespace CTIC\App\RealizationArea\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Company\Domain\Company;

class RealizationAreaCommand implements CommandInterface
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
    public $realizationArea;

    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var Company
     */
    public $company;
}