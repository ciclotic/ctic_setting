<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Sylius\Component\Grid\Parameters;

interface ResourcesCollectionProviderInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param EntityRepositoryInterface $repository
     * @param Parameters $parameters
     *
     * @return mixed
     */
    public function get(RequestConfiguration $requestConfiguration, EntityRepositoryInterface $repository, Parameters $parameters = null);
}
