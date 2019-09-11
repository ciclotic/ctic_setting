<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

interface StateMachineInterface
{
    /**
     * @param RequestConfiguration $configuration
     * @param EntityInterface $resource
     *
     * @return bool
     *
     * @throws
     */
    public function can(RequestConfiguration $configuration, EntityInterface $resource): bool;

    /**
     * @param RequestConfiguration $configuration
     * @param EntityInterface $resource
     *
     * @throws
     */
    public function apply(RequestConfiguration $configuration, EntityInterface $resource): void;
}
