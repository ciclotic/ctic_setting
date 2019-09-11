<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Event\EntityControllerEvent;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

interface FlashHelperInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param string $actionName
     * @param EntityInterface|null $resource
     */
    public function addSuccessFlash(
        RequestConfiguration $requestConfiguration,
        string $actionName,
        ?EntityInterface $resource = null
    ): void;

    /**
     * @param RequestConfiguration $requestConfiguration
     * @param string $actionName
     */
    public function addErrorFlash(RequestConfiguration $requestConfiguration, string $actionName): void;

    /**
     * @param RequestConfiguration $requestConfiguration
     * @param EntityControllerEvent $event
     */
    public function addFlashFromEvent(RequestConfiguration $requestConfiguration, EntityControllerEvent $event): void;
}
