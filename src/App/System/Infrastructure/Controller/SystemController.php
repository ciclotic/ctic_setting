<?php

namespace CTIC\App\System\Infrastructure\Controller;

use CTIC\App\Base\Application\AuthorizationCheckerInterface;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Application\RequestConfigurationFactoryInterface;
use CTIC\App\Base\Application\SingleResourceProviderInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityActions;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\Exception\DeleteHandlingException;
use CTIC\App\Base\Domain\Exception\UpdateHandlingException;
use CTIC\App\Base\Domain\MetadataInterface;
use CTIC\App\Base\Infrastructure\Controller\Command\ResourceControllerCommand;
use CTIC\App\Base\Infrastructure\Event\EntityControllerEvent;
use CTIC\App\Base\Infrastructure\Event\EventDispatcherInterface;
use CTIC\App\Base\Infrastructure\Form\ResourceFormFactoryInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Base\Infrastructure\View\ResourceGridView;
use CTIC\App\Base\Infrastructure\View\Rest\View;
use CTIC\App\Base\Infrastructure\View\Rest\ViewHandlerInterface;
use CTIC\App\System\Domain\System;
use CTIC\App\System\Infrastructure\Repository\SystemRepository;
use CTIC\App\User\Domain\User;
use Doctrine\ORM\EntityManager;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;
use Sylius\Component\Grid\Parameters;
use CTIC\App\Base\Infrastructure\View\GridView;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;

class SystemController extends ResourceController
{
    /**
     * @var EntityInterface|System|null
     */
    protected $resource;

    /**
     * @var EntityRepository|SystemRepository
     */
    protected $repository;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resource = $this->repository->findOneRandom();
        $this->prepareUpdateEntity($resource, $configuration);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest()->isValid()) {
            $resource = $form->getData();

            $this->completeUpdateEntity($resource, $configuration);

            /** @var EntityControllerEvent $event */
            $event = $this->eventDispatcher->dispatchPreEvent(EntityActions::UPDATE, $configuration, $resource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return $this->redirectHandler->redirectToResource($configuration, $resource);
            }

            try {
                $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
            } catch (\Exception $exception) {
                if (!$configuration->isHtmlRequest()) {
                    return $this->viewHandler->handle(
                        View::create($form, $exception->getCode()),
                        $configuration->getRequest()
                    );
                }

                $this->flashHelper->addErrorFlash($configuration, $exception->getMessage());

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $this->postUpdateEntity($resource, $configuration);

            $postEvent = $this->eventDispatcher->dispatchPostEvent(EntityActions::UPDATE, $configuration, $resource);

            if (!$configuration->isHtmlRequest()) {
                $view = $configuration->getParameters()->get('return_content', false) ? View::create($resource, Response::HTTP_OK) : View::create(null, Response::HTTP_NO_CONTENT);

                return $this->viewHandler->handle($view, $configuration->getRequest());
            }

            $this->flashHelper->addSuccessFlash($configuration, EntityActions::UPDATE, $resource);

            if ($postEvent->hasResponse()) {
                return $postEvent->getResponse();
            }

            return $this->redirectHandler->redirectToRoute($configuration, 'dashboard');
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle(View::create($form, Response::HTTP_BAD_REQUEST), $configuration->getRequest());
        }

        $this->eventDispatcher->dispatchInitializeEvent(EntityActions::UPDATE, $configuration, $resource);

        $formView = $form->createView();
        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'form' => $formView,
            ])
            ->setTemplate($configuration->getTemplate($this->getResourceName() . '/' . EntityActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }
}