<?php

namespace CTIC\App\User\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\User\Domain\User;
use CTIC\App\User\Domain\UserInterface;

class UserController extends ResourceController
{
    /**
     * @param EntityInterface|User $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $password = $resource->getPassword();

        if (!empty($password)) {
            $resource->password = md5($password);
        }
    }

    /**
     * @param EntityInterface|User $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->completeEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|User $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->completeEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|User $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $resource->password = null;
    }
}