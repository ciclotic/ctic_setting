<?php

namespace CTIC\App\Base\Application;


use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;

interface CreateInterface
{
    /**
     * @param CommandInterface $command
     * @return EntityInterface
     */
    public static function create(CommandInterface $command): EntityInterface;
}