<?php
namespace CTIC\App\Permission\Domain;

use CTIC\App\Base\Domain\BasePermissionInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;
use CTIC\App\User\Domain\User;

interface PermissionInterface extends IdentifiableInterface, EntityInterface, BasePermissionInterface
{
}