<?php
namespace CTIC\App\Privacy\Domain;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface PrivacyInterface extends IdentifiableInterface, EntityInterface
{
    const TYPE_CREATOR = 1;
    const TYPE_CREATOR_AND_ASIGNED_USERS = 2;
    const TYPE_PUBLIC = 3;

    public function isOwn(): bool;

    public function getType(): int;
    public function setType($type): bool;
}