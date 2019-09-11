<?php

namespace CTIC\App\Base\Domain;

interface BasePermissionInterface
{
    const TYPE_ADMINISTRATOR = 1;
    const TYPE_USER = 2;
    const TYPE_EMPLOYEE = 3;
    const TYPE_ANONYMOUS = 4;

    public function getRoute(): string;

    public function getType(): int;
    public function setType($type): bool;
}