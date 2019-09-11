<?php
namespace CTIC\App\Permission\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Permission\Domain\Permission;

class PermissionCommand implements CommandInterface
{
    /**
     * @var string
     */
    public $route;

    /**
     * @var int
     */
    public $type;
}