<?php

namespace CTIC\App\Base\Infrastructure\Event;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

interface EventDispatcherInterface
{
    /**
     * @param string $eventName
     * @param RequestConfiguration $requestConfiguration
     * @param EntityInterface $resource
     *
     * @return EntityControllerEvent
     */
    public function dispatch(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        EntityInterface $resource
    ): EntityControllerEvent;

    /**
     * @param string $eventName
     * @param RequestConfiguration $requestConfiguration
     * @param mixed $resources
     *
     * @return EntityControllerEvent
     */
    public function dispatchMultiple(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        $resources
    ): EntityControllerEvent;

    /**
     * @param string $eventName
     * @param RequestConfiguration $requestConfiguration
     * @param EntityInterface $resource
     *
     * @return EntityControllerEvent
     */
    public function dispatchPreEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        EntityInterface $resource
    ): EntityControllerEvent;

    /**
     * @param string $eventName
     * @param RequestConfiguration $requestConfiguration
     * @param EntityInterface $resource
     *
     * @return EntityControllerEvent
     */
    public function dispatchPostEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        EntityInterface $resource
    ): EntityControllerEvent;

    /**
     * @param string $eventName
     * @param RequestConfiguration $requestConfiguration
     * @param EntityInterface $resource
     *
     * @return EntityControllerEvent
     */
    public function dispatchInitializeEvent(
        string $eventName,
        RequestConfiguration $requestConfiguration,
        EntityInterface $resource
    ): EntityControllerEvent;
}
