<?php

namespace CTIC\App\Base\Application;


use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Doctrine\ORM\EntityRepository;

interface SingleResourceProviderInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param EntityRepository $repository
     *
     * @return EntityInterface|null
     */
    public function get(RequestConfiguration $requestConfiguration, EntityRepository $repository): ?EntityInterface;
}
