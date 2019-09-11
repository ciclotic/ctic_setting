<?php

namespace CTIC\App\Base\Infrastructure\Repository;

use CTIC\App\Base\Domain\BasePermissionInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Doctrine\ORM\EntityManager;

interface PermissionRepositoryInterface
{
    /**
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * @param RequestConfiguration $configuration
     *
     * @return EntityInterface|BasePermissionInterface
     */
    public function findPermissionByRequestConfiguration(RequestConfiguration $configuration): ?EntityInterface;
}