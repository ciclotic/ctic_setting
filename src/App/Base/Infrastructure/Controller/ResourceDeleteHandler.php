<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;

final class ResourceDeleteHandler implements ResourceDeleteHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(EntityInterface $resource, EntityRepositoryInterface $repository): void
    {
        $repository->remove($resource);
    }
}
