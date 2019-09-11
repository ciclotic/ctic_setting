<?php
namespace CTIC\App\Privacy\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Privacy\Domain\Privacy;

class PrivacyCommand implements CommandInterface
{
    /**
     * @var bool
     */
    public $root;

    /**
     * @var bool
     */
    public $own;

    /**
     * @var Privacy
     */
    public $parent;
}