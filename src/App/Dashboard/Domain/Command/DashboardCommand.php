<?php
namespace CTIC\App\Dashboard\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;

class DashboardCommand implements CommandInterface
{
    /**
     * @var string
     */
    public $name;
}