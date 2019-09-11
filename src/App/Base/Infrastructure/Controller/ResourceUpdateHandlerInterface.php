<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\Base\Domain\EntityInterface;

interface ResourceUpdateHandlerInterface
{
    /**
     * @param EntityInterface $resource
     * @param RequestConfiguration $requestConfiguration
     * @param ObjectManager $manager
     */
    public function handle(
        EntityInterface $resource,
        RequestConfiguration $requestConfiguration,
        ObjectManager $manager
    ): void;
}
