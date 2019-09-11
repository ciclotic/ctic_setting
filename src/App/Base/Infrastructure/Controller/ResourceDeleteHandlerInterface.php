<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;

interface ResourceDeleteHandlerInterface
{
    /**
     * @param EntityInterface $resource
     * @param EntityRepositoryInterface $repository
     */
    public function handle(EntityInterface $resource, EntityRepositoryInterface $repository): void;
}
