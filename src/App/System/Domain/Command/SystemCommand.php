<?php
namespace CTIC\App\System\Domain\Command;

use CTIC\App\Base\Domain\Command\CommandInterface;

class SystemCommand implements CommandInterface
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
    public $styleTPV;

    /**
     * @var bool
     */
    public $enabledTPVSound;

    /**
     * @var bool
     */
    public $enabledRoundDiscount;

    /**
     * @var int
     */
    public $numberTPVFamilyLines;

    /**
     * @var int
     */
    public $numberTPVProductLines;

    /**
     * @var bool
     */
    public $enabledTPVAutomaticComplements;

    /**
     * @var int
     */
    public $typeTPVPrint;

    /**
     * @var int
     */
    public $typeClientName;

    /**
     * @var bool
     */
    public $enabled;
}