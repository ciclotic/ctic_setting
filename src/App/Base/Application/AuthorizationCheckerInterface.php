<?php

namespace CTIC\App\Base\Application;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

interface AuthorizationCheckerInterface
{
    /**
     * @param RequestConfiguration $configuration
     *
     * @return bool
     */
    public function isGranted(RequestConfiguration $configuration): bool;
}