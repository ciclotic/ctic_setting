<?php
namespace CTIC\App\Account\Domain;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface AccountInterface extends IdentifiableInterface, EntityInterface
{
    public function getName(): string;
}