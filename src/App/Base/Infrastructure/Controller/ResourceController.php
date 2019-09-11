<?php

namespace CTIC\App\Base\Infrastructure\Controller;

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

class ResourceController implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ControllerTrait;

    /**
     * @var EntityInterface|null
     */
    protected $resource;

    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var RedirectHandlerInterface
     */
    protected $redirectHandler;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var FlashHelperInterface
     */
    protected $flashHelper;

    /**
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    /**
     * @var ResourceUpdateHandlerInterface
     */
    protected $resourceUpdateHandler;

    /**
     * @var ResourceDeleteHandlerInterface
     */
    protected $resourceDeleteHandler;

    /**
     * @var StateMachineInterface
     */
    protected $stateMachine;

    /**
     * @var ResourceFormFactoryInterface
     */
    protected $resourceFormFactory;

    /**
     * @var CreateInterface
     */
    protected $newResourceFactory;

    /**
     * @var ResourcesCollectionProviderInterface
     */
    protected $resourcesCollectionProvider;

    /**
     * @var RequestConfigurationFactoryInterface
     */
    protected $requestConfigurationFactory;

    /**
     * @var SingleResourceProviderInterface
     */
    protected $singleResourceProvider;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * ResourceController constructor.
     * @param ResourceControllerCommand $command
     */
    public function __construct(
        ResourceControllerCommand $command
    ) {
        $this->repository = $command->repository;
        $this->metadata = $command->metadata;
        $this->authorizationChecker = $command->authorizationChecker;
        $this->redirectHandler = $command->redirectHandler;
        $this->eventDispatcher = $command->eventDispatcher;
        $this->flashHelper = $command->flashHelper;
        $this->viewHandler = $command->viewHandler;
        $this->resourceUpdateHandler = $command->resourceUpdateHandler;
        $this->resourceDeleteHandler = $command->resourceDeleteHandler;
        $this->stateMachine = $command->stateMachine;
        $this->resourceFormFactory = $command->resourceFormFactory;
        $this->newResourceFactory = $command->newResourceFactory;
        $this->resourcesCollectionProvider = $command->resourcesCollectionProvider;
        $this->requestConfigurationFactory = $command->requestConfigurationFactory;
        $this->singleResourceProvider = $command->singleResourceProvider;
        $this->manager = $command->manager;
        $this->command = $command->command;
        $this->resource = $command->resource;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        $className = $this->resource;
        if(is_object($className)) {
            $className = get_class($this->resource);
        }

        return substr(strrchr($className, "\\"), 1);
    }

    /**
     * @param RequestConfiguration $configuration
     *
     * @return Response
     */
    protected function redirectToLogin(RequestConfiguration $configuration): Response
    {
        return $this->redirectHandler->redirectToRoute($configuration, 'login');
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function showAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resource = $this->findOr404($configuration);

        $this->eventDispatcher->dispatch(EntityActions::SHOW, $configuration, $resource);

        $view = View::create($resource);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate($this->getResourceName() . '/' . EntityActions::SHOW . '.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    $this->metadata->getName() => $resource,
                ])
            ;
        }

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $parametersToAdd = array();
        if ($this->metadata->hasParameter('grid')) {
            $parametersGrid = $this->metadata->getParameter('grid');
            foreach ($parametersGrid as $key => $parameterGrid)
            {
                $parametersToAdd[$key] = $parameterGrid;
            }
        }
        $parameters = new Parameters($parametersToAdd);
        /** @var ResourceGridView $resources */
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository, $parameters);

        $this->eventDispatcher->dispatchMultiple(EntityActions::INDEX, $configuration, $resources);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $parametersToGridView = new Parameters($configuration->getParameters()->all());
            $gridView = new GridView($resources, $resources->getDefinition(), $parametersToGridView);
            $view
                ->setTemplate($configuration->getTemplate($this->getResourceName() . '/' . EntityActions::INDEX . '.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'configuration'                     => $configuration,
                    'metadata'                          => $this->metadata,
                    'resources'                         => $resources,
                    'gridview'                          => $gridView,
                    $this->metadata->getPluralName()    => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function downloadAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $parametersToAdd = array();
        if ($this->metadata->hasParameter('grid')) {
            $parametersGrid = $this->metadata->getParameter('grid');
            foreach ($parametersGrid as $key => $parameterGrid)
            {
                $parametersToAdd[$key] = $parameterGrid;
            }
        }
        $parameters = new Parameters($parametersToAdd);
        /** @var ResourceGridView $resources */
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository, $parameters);

        $this->eventDispatcher->dispatchMultiple(EntityActions::INDEX, $configuration, $resources);

        /*header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");*/

        $return = '';
        $first = true;
        $data = $resources->getData();
        foreach ($data as $resource) {
            if ($first) {
                $first = false;
                foreach ($resource as $key => $element) {
                    $return = $return . $key . ';';
                }
            }
            $return = $return . "\n";
            foreach ($resource as $key => $element) {
                if (is_object($element) && get_class($element) == 'DateTime') {
                    $return = $return . $element->format('Y-m-d') . ';';
                } elseif (is_object($element) && get_class($element) == 'Doctrine\ORM\PersistentCollection')
                {
                    foreach ($element as $childElement) {
                        $return = $return . $childElement . ',';
                    }

                    $return = $return . ';';
                } else {
                    $return = $return . $element . ';';
                }
            }
        }

        $response = new Response($return, 200, array(
            "Content-type"          => 'text/csv',
            "Content-Disposition"   => 'attachment; filename=file.csv',
            "Pragma"                => 'no-cache',
            "Expires"               => '0'
        ));

        return $response;
    }

    /**
     * @param EntityInterface $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface|Event $resource
     * @param RequestConfiguration $configuration
     */
    protected function postCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param EntityInterface|Event $resource
     * @param RequestConfiguration $configuration
     */
    protected function postUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $newResource = $this->newResourceFactory->create($this->command);
        $this->prepareCreateEntity($newResource, $configuration);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST') && $form->handleRequest()->isValid()) {
            $newResource = $form->getData();

            $this->completeCreateEntity($newResource, $configuration);

            $event = $this->eventDispatcher->dispatchPreEvent(EntityActions::CREATE, $configuration, $newResource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return $this->redirectHandler->redirectToIndex($configuration, $newResource);
            }

            if ($configuration->hasStateMachine()) {
                $this->stateMachine->apply($configuration, $newResource);
            }

            $this->repository->add($newResource);
            $this->postCreateEntity($newResource, $configuration);
            $postEvent = $this->eventDispatcher->dispatchPostEvent(EntityActions::CREATE, $configuration, $newResource);

            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle(View::create($newResource, Response::HTTP_CREATED), $configuration->getRequest());
            }

            $this->flashHelper->addSuccessFlash($configuration, EntityActions::CREATE, $newResource);

            if ($postEvent->hasResponse()) {
                return $postEvent->getResponse();
            }

            return $this->redirectHandler->redirectToIndex($configuration);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle(View::create($form, Response::HTTP_BAD_REQUEST), $configuration->getRequest());
        }

        $this->eventDispatcher->dispatchInitializeEvent(EntityActions::CREATE, $configuration, $newResource);

        $formView = $form->createView();
        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $newResource,
                $this->metadata->getName() => $newResource,
                'form' => $formView,
            ])
            ->setTemplate($configuration->getTemplate($this->getResourceName() . '/' . EntityActions::CREATE . '.html'))
        ;

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function updateAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resource = $this->findOr404($configuration);
        $this->prepareUpdateEntity($resource, $configuration);

        $session = $configuration->getRequest()->getSession();
        $identity = $session->get('identity');
        $dbName = $session->get('dbName');
        if (empty($identity) || empty($identity['data']['id']) || empty($dbName)) {
            throw new \Exception('Cannot get identity to update entity');
        }

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

            return $this->redirectHandler->redirectToIndex($configuration);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle(View::create($form, Response::HTTP_BAD_REQUEST), $configuration->getRequest());
        }

        $this->eventDispatcher->dispatchInitializeEvent(EntityActions::UPDATE, $configuration, $resource);

        $formView = $form->createView();
        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'dbName' => $dbName,
                'identity' => $identity,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'form' => $formView,
            ])
            ->setTemplate($configuration->getTemplate($this->getResourceName() . '/' . EntityActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resource = $this->findOr404($configuration);

        $event = $this->eventDispatcher->dispatchPreEvent(EntityActions::DELETE, $configuration, $resource);

        if ($event->isStopped() && !$configuration->isHtmlRequest()) {
            throw new HttpException($event->getErrorCode(), $event->getMessage());
        }
        if ($event->isStopped()) {
            $this->flashHelper->addFlashFromEvent($configuration, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            return $this->redirectHandler->redirectToIndex($configuration, $resource);
        }

        try {
            $this->resourceDeleteHandler->handle($resource, $this->repository);
        } catch (\Exception $exception) {
            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle(
                    View::create(null, $exception->getCode()),
                    $configuration->getRequest()
                );
            }

            $this->flashHelper->addErrorFlash($configuration, $exception->getMessage());

            return $this->redirectHandler->redirectToReferer($configuration);
        }

        $postEvent = $this->eventDispatcher->dispatchPostEvent(EntityActions::DELETE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle(View::create(null, Response::HTTP_NO_CONTENT), $configuration->getRequest());
        }

        $this->flashHelper->addSuccessFlash($configuration, EntityActions::DELETE, $resource);

        if ($postEvent->hasResponse()) {
            return $postEvent->getResponse();
        }

        return $this->redirectHandler->redirectToIndex($configuration);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function bulkDeleteAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $this->eventDispatcher->dispatchMultiple(EntityActions::BULK_DELETE, $configuration, $resources);

        foreach ($resources as $resource) {
            $event = $this->eventDispatcher->dispatchPreEvent(EntityActions::DELETE, $configuration, $resource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return $this->redirectHandler->redirectToIndex($configuration, $resource);
            }

            try {
                $this->resourceDeleteHandler->handle($resource, $this->repository);
            } catch (\Exception $exception) {
                if (!$configuration->isHtmlRequest()) {
                    return $this->viewHandler->handle(
                        View::create(null, $exception->getCode()),
                        $configuration->getRequest()
                    );
                }

                $this->flashHelper->addErrorFlash($configuration, $exception->getMessage());

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $postEvent = $this->eventDispatcher->dispatchPostEvent(EntityActions::DELETE, $configuration, $resource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle(View::create(null, Response::HTTP_NO_CONTENT), $configuration->getRequest());
        }

        $this->flashHelper->addSuccessFlash($configuration, EntityActions::BULK_DELETE);

        if (isset($postEvent) && $postEvent->hasResponse()) {
            return $postEvent->getResponse();
        }

        return $this->redirectHandler->redirectToIndex($configuration);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function applyStateMachineTransitionAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resource = $this->findOr404($configuration);

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

        if (!$this->stateMachine->can($configuration, $resource)) {
            throw new BadRequestHttpException();
        }

        try {
            $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
        } catch (\Exception $exception) {
            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle(
                    View::create($resource, $exception->getCode()),
                    $configuration->getRequest()
                );
            }

            $this->flashHelper->addErrorFlash($configuration, $exception->getMessage());

            return $this->redirectHandler->redirectToReferer($configuration);
        }

        $postEvent = $this->eventDispatcher->dispatchPostEvent(EntityActions::UPDATE, $configuration, $resource);

        if (!$configuration->isHtmlRequest()) {
            $view = $configuration->getParameters()->get('return_content', true) ? View::create($resource, Response::HTTP_OK) : View::create(null, Response::HTTP_NO_CONTENT);

            return $this->viewHandler->handle($view, $configuration->getRequest());
        }

        $this->flashHelper->addSuccessFlash($configuration, EntityActions::UPDATE, $resource);

        if ($postEvent->hasResponse()) {
            return $postEvent->getResponse();
        }

        return $this->redirectHandler->redirectToResource($configuration, $resource);
    }

    /**
     * @param RequestConfiguration $configuration
     *
     * @return bool
     *
     * @throws AccessDeniedException
     */
    protected function isGrantedOr403(RequestConfiguration $configuration): bool
    {
        if (!$configuration->hasPermission()) {
            return false;
        }

        if (!$this->authorizationChecker->isGranted($configuration)) {
            return false;
        }

        return true;
    }

    /**
     * @param RequestConfiguration $configuration
     *
     * @return EntityInterface
     *
     * @throws NotFoundHttpException
     */
    protected function findOr404(RequestConfiguration $configuration): EntityInterface
    {
        if (null === $resource = $this->singleResourceProvider->get($configuration, $this->repository)) {
            throw new NotFoundHttpException(sprintf('El "%s" no se ha encontrado', $this->metadata->getHumanizedName()));
        }

        return $resource;
    }
}
