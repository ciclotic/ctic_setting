<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Symfony\Component\HttpFoundation\Response;

interface RedirectHandlerInterface
{
    /**
     * @param RequestConfiguration $configuration
     * @param EntityInterface $resource
     *
     * @return Response
     */
    public function redirectToResource(RequestConfiguration $configuration, EntityInterface $resource): Response;

    /**
     * @param RequestConfiguration $configuration
     * @param EntityInterface|null $resource
     *
     * @return Response
     */
    public function redirectToIndex(RequestConfiguration $configuration, ?EntityInterface $resource = null): Response;

    /**
     * @param RequestConfiguration $configuration
     * @param string               $route
     * @param array                $parameters
     *
     * @return Response
     */
    public function redirectToRoute(RequestConfiguration $configuration, string $route, array $parameters = []): Response;

    /**
     * @param RequestConfiguration $configuration
     * @param string $url
     * @param int $status
     *
     * @return Response
     */
    public function redirect(RequestConfiguration $configuration, string $url, int $status = 302): Response;

    /**
     * @param RequestConfiguration $configuration
     *
     * @return Response
     */
    public function redirectToReferer(RequestConfiguration $configuration): Response;
}
