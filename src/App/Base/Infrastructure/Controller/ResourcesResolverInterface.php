<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

interface ResourcesResolverInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param EntityRepositoryInterface $repository
     *
     * @return mixed
     */
    public function getResources(RequestConfiguration $requestConfiguration, EntityRepositoryInterface $repository);
}
