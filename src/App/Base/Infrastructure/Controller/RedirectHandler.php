<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityActions;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

final class RedirectHandler implements RedirectHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToResource(RequestConfiguration $configuration, EntityInterface $resource): Response
    {
        try {
            return $this->redirectToRoute(
                $configuration,
                $configuration->getRedirectRoute(EntityActions::SHOW),
                $configuration->getRedirectParameters($resource)
            );
        } catch (RouteNotFoundException $exception) {
            return $this->redirectToRoute(
                $configuration,
                $configuration->getRedirectRoute(EntityActions::INDEX),
                $configuration->getRedirectParameters($resource)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToIndex(RequestConfiguration $configuration, ?EntityInterface $resource = null): Response
    {
        return $this->redirectToRoute(
            $configuration,
            $configuration->getRedirectRoute(EntityActions::INDEX),
            $configuration->getRedirectParameters($resource)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToRoute(RequestConfiguration $configuration, string $route, array $parameters = []): Response
    {
        if ('referer' === $route) {
            return $this->redirectToReferer($configuration);
        }

        return $this->redirect($configuration, $this->router->generate($route, $parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(RequestConfiguration $configuration, string $url, int $status = 302): Response
    {
        if ($configuration->isHeaderRedirection()) {
            return new Response('', 200);
        }

        return new RedirectResponse($url . $configuration->getRedirectHash(), $status);
    }

    /**
     * {@inheritdoc}
     */
    public function redirectToReferer(RequestConfiguration $configuration): Response
    {
        return $this->redirect($configuration, $configuration->getRedirectReferer());
    }
}
