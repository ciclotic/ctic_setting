<?php
namespace CTIC\App\Account\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;

class AccountCommand implements CommandInterface
{
    /**
     * @var string
     */
    public $name;
}