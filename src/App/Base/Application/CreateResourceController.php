<?php
namespace CTIC\App\Base\Application;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\Metadata;
use CTIC\App\Base\Infrastructure\Controller\Command\ResourceControllerCommand;
use CTIC\App\Base\Infrastructure\Controller\FlashHelper;
use CTIC\App\Base\Infrastructure\Controller\RedirectHandler;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\Base\Infrastructure\Controller\ResourceDeleteHandler;
use CTIC\App\Base\Infrastructure\Controller\ResourcesCollectionProvider;
use CTIC\App\Base\Infrastructure\Controller\ResourcesResolver;
use CTIC\App\Base\Infrastructure\Controller\ResourceUpdateHandler;
use CTIC\App\Base\Infrastructure\Controller\StateMachine;
use CTIC\App\Base\Infrastructure\Doctrine\Form\Type\EntityType;
use CTIC\App\Base\Infrastructure\Event\EventDispatcher;
use CTIC\App\Base\Infrastructure\Form\Registry\FormTypeRegistry;
use CTIC\App\Base\Infrastructure\Form\ResourceFormFactory;
use CTIC\App\Base\Infrastructure\Grid\FieldType\DateTimeFieldType;
use CTIC\App\Base\Infrastructure\Grid\FieldType\EnabledFieldType;
use CTIC\App\Base\Infrastructure\Renderer\TwigBulkActionGridRenderer;
use CTIC\App\Base\Infrastructure\Renderer\TwigGridRenderer;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;
use CTIC\App\Base\Infrastructure\Repository\PermissionRepositoryInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Base\Infrastructure\Serializer\Serializer;
use CTIC\App\Base\Infrastructure\Templating\EngineInterface;
use CTIC\App\Base\Infrastructure\Templating\Helper\BulkActionGridHelper;
use CTIC\App\Base\Infrastructure\Templating\Helper\GridHelper;
use CTIC\App\Base\Infrastructure\Templating\TwigEngine;
use CTIC\App\Base\Infrastructure\Twig\BulkActionGridExtension;
use CTIC\App\Base\Infrastructure\Twig\GridExtension;
use CTIC\App\Base\Infrastructure\Twig\PagerfantaExtension;
use CTIC\App\Base\Infrastructure\Twig\RoutingExtension;
use CTIC\App\Base\Infrastructure\View\Rest\ViewHandler;
use Doctrine\ORM\EntityManager;
use Hateoas\Representation\Factory\PagerfantaFactory;
use JMS\Serializer\Construction\UnserializeObjectConstructor;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Metadata\Driver\YamlDriver;
use Metadata\Driver\FileLocatorInterface;
use Metadata\MetadataFactory;
use Pagerfanta\View\ViewFactory;
use PhpCollection\Map;
use Sylius\Component\Grid\DataExtractor\PropertyAccessDataExtractor;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Sylius\Component\Grid\FieldTypes\StringFieldType;
use Sylius\Component\Registry\ServiceRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SM\Factory\Factory;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;
use CTIC\App\Base\Infrastructure\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

abstract class CreateResourceController implements CreateResourceControllerInterface
{
    /**
     * @param string $name
     * @param string $applicationName
     * @param array $parameters
     *
     * @return Metadata
     */
    public static function getMetadata(
        $name = 'ResourceController',
        $applicationName = 'App',
        $parameters = array(
            'permission'    => true,
            'repository'    => '',
            'driver'        => '',
            'templates'     => '',
            'header'        => '',
            'route'         => array(
                'root'      => '',
                'actions'   => array(
                    'list'  => '',
                    'show'  => '',
                    'create'    =>'',
                    'update'    =>'',
                    'delete'    =>'',
                    'bulkDelete'  => '',
                    'download'    => ''
                )
            ),
            'classes'       => array(
                'form'      => ''
            ),
            'grid'          => array(
                'actionGroups'  => array(
                    'bulk'  => array(
                        'delete'  => array(
                            'type'      => 'delete',
                            'enabled'   => true,
                            'position'  => 10,
                            'name'      => 'delete',
                            'label'     => 'Delete'
                        )
                    ),
                    'item'  => array(
                        'show'  => array(
                            'type'      => 'show',
                            'enabled'   => true,
                            'position'  => 10,
                            'name'      => 'show',
                            'label'     => 'Show'
                        ),
                        'edit'  => array(
                            'type'      => 'edit',
                            'enabled'   => true,
                            'position'  => 20,
                            'name'      => 'edit',
                            'label'     => 'Edit'
                        ),
                        'delete'  => array(
                            'type'      => 'delete',
                            'enabled'   => true,
                            'position'  => 30,
                            'name'      => 'delete',
                            'label'     => 'Delete'
                        )
                    )
                ),
                'paginate'  => true,
                'limit'     => 10,
                'sortable'  => true,
                'sorting'   => array(
                    'id'    => 'ASC'
                ),
                'fields'    => array(
                    'id'    => array(
                        'type'          => 'text',
                        'enabled'       => true,
                        'isSortable'    => true,
                        'name'          => 'id',
                        'label'         => 'Id',
                        'position'      => '10'
                    ),
                    'name'    => array(
                        'type'          => 'text',
                        'isSortable'    => true,
                        'name'          => 'name',
                        'label'         => 'Name',
                        'position'      => '20'
                    )
                ),
                'filterable' => true,
                'filters'    => array(
                    'id'    => array(
                        'type'          => 'text',
                        'enabled'       => true,
                        'name'          => 'id',
                        'label'         => 'Id',
                        'position'      => '10'
                    ),
                    'name'    => array(
                        'type'          => 'text',
                        'enabled'       => true,
                        'name'          => 'name',
                        'label'         => 'Name',
                        'position'      => '20'
                    )
                )
            )
        )
    ): Metadata
    {
        $metadata = Metadata::fromAliasAndConfiguration($applicationName . '.' . $name, $parameters);

        return $metadata;
    }

    /**
     * @param PermissionRepositoryInterface $permissionRepository
     *
     * @return AuthorizationChecker
     */
    public static function getAuthorizationChecker(
        PermissionRepositoryInterface $permissionRepository
    ): AuthorizationChecker
    {
        $authorizationChecker = new AuthorizationChecker($permissionRepository);

        return $authorizationChecker;
    }

    /**
     * @param RouterInterface $router
     *
     * @return RedirectHandler
     */
    public static function getRedirectHandler(
        RouterInterface $router
    ): RedirectHandler
    {
        $redirectHandler = new RedirectHandler($router);

        return $redirectHandler;
    }

    /**
     * @return EventDispatcher
     */
    public static function getEventDispatcher(): EventDispatcher
    {
        $symfonyEventDispatcher = new SymfonyEventDispatcher();
        $eventDispatcher = new EventDispatcher($symfonyEventDispatcher);

        return $eventDispatcher;
    }

    /**
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param string $defaultLocale
     *
     * @return FlashHelper
     */
    public static function getFlashHelper(
        SessionInterface $session,
        TranslatorInterface $translator,
        $defaultLocale
    ): FlashHelper
    {
        $flashHelper = new FlashHelper($session, $translator, $defaultLocale);

        return $flashHelper;
    }

    /**
     * @param FormRegistry $formRegistry
     * @param ResourceControllerCommand $command
     *
     * @return void
     */
    public static function completeFormRegistry(
        FormRegistry $formRegistry,
        ResourceControllerCommand $command
    ): void
    {
        $formRegistry->addType(new EntityType($command->manager));
    }

    /**
     * @param ResourceControllerCommand $command
     *
     * @return FormFactory
     */
    public static function getFormFactory(ResourceControllerCommand $command): FormFactory
    {
        $resolvedFormTypeFactory = new ResolvedFormTypeFactory();
        $formRegistry = new FormRegistry(array(), $resolvedFormTypeFactory);
        static::completeFormRegistry($formRegistry, $command);
        $formFactory = new FormFactory($formRegistry);

        return $formFactory;
    }

    /**
     * @param FormTypeRegistry $formTypeRegistry
     *
     * @return void
     */
    public static function completeFormTypeRegistry(
        FormTypeRegistry $formTypeRegistry
    ): void
    {
        $formTypeRegistry->add('text', 'TextType', TextType::class);
        $formTypeRegistry->add('dateTime', 'DateTimeType', DateTimeType::class);
        $formTypeRegistry->add('checkbox', 'CheckboxType', CheckboxType::class);
        $formTypeRegistry->add('choice', 'ChoiceType', ChoiceType::class);
        $formTypeRegistry->add('entity', 'EntityType', EntityType::class);
    }

    /**
     * @return FormTypeRegistry
     */
    public static function getFormTypeRegistry(): FormTypeRegistry
    {
        $formTypeRegistry = new FormTypeRegistry();
        static::completeFormTypeRegistry($formTypeRegistry);

        return $formTypeRegistry;
    }

    /**
     * @param \Twig_Environment $twig
     * @param ResourceControllerCommand $command
     * @param RouterInterface $router
     *
     * @return void
     */
    public static function addEngineExtensions(
        $twig,
        ResourceControllerCommand $command,
        RouterInterface $router
    ): void
    {
        // Add grid extension
        $formFactory = self::getFormFactory($command);
        $propertyAccessor = new PropertyAccessor();
        $dataExtractor = new PropertyAccessDataExtractor($propertyAccessor);
        $stringFieldType = new StringFieldType($dataExtractor);
        $enabledFieldType = new EnabledFieldType($dataExtractor);
        $dateTimeFieldType = new DateTimeFieldType($dataExtractor);
        $fieldsRegistry = new ServiceRegistry(FieldTypeInterface::class);
        $fieldsRegistry->register('text', $stringFieldType);
        $fieldsRegistry->register('enabled', $enabledFieldType);
        $fieldsRegistry->register('dateTime', $dateTimeFieldType);
        $formTypeRegistry = self::getFormTypeRegistry();
        $defaultTemplate = '/Grid/_default.html.twig';
        $actionTemplate = array(
            'show'      => '/Grid/Action/_show.html.twig',
            'edit'      => '/Grid/Action/_edit.html.twig',
            'delete'    => '/Grid/Action/_delete.html.twig'
        );
        $filterTemplate = array(
            'checkbox'      => '/Grid/Filter/_checkbox.html.twig',
            'choice'        => '/Grid/Filter/_choice.html.twig',
            'date'          => '/Grid/Filter/_date.html.twig',
            'dateTime'      => '/Grid/Filter/_dateTime.html.twig',
            'entity'        => '/Grid/Filter/_entity.html.twig',
            'exists'        => '/Grid/Filter/_exists.html.twig',
            'text'          => '/Grid/Filter/_text.html.twig'
        );
        $gridRender = new TwigGridRenderer($twig, $fieldsRegistry, $formFactory, $formTypeRegistry, $defaultTemplate, $actionTemplate, $filterTemplate);
        $gridHelper = new GridHelper($gridRender);
        $twig->addExtension(new GridExtension($gridHelper));
        $twig->addFilter( new \Twig_SimpleFilter('cast_to_array', function ($stdClassObject) {
            $response = array();
            foreach ($stdClassObject as $key => $value) {
                $response[] = array($key, $value);
            }
            return $response;
        }));

        // Add bulk action grid extension
        $bulkActionTemplate = array(
            'delete'    => '/Grid/BulkAction/_delete.html.twig',
            'download'      => '/Grid/BulkAction/_download.html.twig'
        );
        $gridRender = new TwigBulkActionGridRenderer($twig, $bulkActionTemplate);
        $gridHelper = new BulkActionGridHelper($gridRender);
        $twig->addExtension(new BulkActionGridExtension($gridHelper));

        // Add pagerfanta extension
        $defaultTemplatePagerFanta = '';
        $viewFactory = new ViewFactory();
        $requestContext = $router->getContext();
        $urlGenerator = new UrlGenerator($router->getRouteCollection(), $requestContext);
        $twig->addExtension(new PagerfantaExtension($defaultTemplatePagerFanta, $viewFactory, $urlGenerator));

        // Add routing extension
        $requestContext = $router->getContext();
        $urlGenerator = new UrlGenerator($router->getRouteCollection(), $requestContext);
        $twig->addExtension(new RoutingExtension($urlGenerator));
    }

    /**
     * @param array $twigParams
     * @param ResourceControllerCommand $command
     * @param RouterInterface $router
     *
     * @return EngineInterface
     */
    public static function getEngine(
        array $twigParams,
        ResourceControllerCommand $command,
        RouterInterface $router
    ): EngineInterface
    {
        $loader = new \Twig_Loader_Filesystem($twigParams['path']);
        $twig = new \Twig_Environment($loader, array(
            'cache' => (empty($twigParams['cache']))? false : $twigParams['cache']
        ));

        // Add grid extension
        self::addEngineExtensions($twig, $command, $router);

        $engine = new TwigEngine($loader, $twig);

        return $engine;
    }

    /**
     * @param RouterInterface $router
     * @param FileLocatorInterface $fileLocator
     * @param Request $request
     * @param EngineInterface $engine
     *
     * @return ViewHandler
     */
    public static function getViewHandler(
        RouterInterface $router,
        FileLocatorInterface $fileLocator,
        Request $request,
        EngineInterface $engine
    ): ViewHandler
    {
        $requestContext = new RequestContext();
        $urlGenerator = new UrlGenerator($router->getRouteCollection(), $requestContext);
        $driver = new YamlDriver($fileLocator);
        $metadataFactory = new MetadataFactory($driver, Metadata::class);
        $handlerRegistry = new HandlerRegistry();
        $objectConstructor = new UnserializeObjectConstructor();
        $serializationVisitor = new Map();
        $deserializationVisitor = new Map();
        $serializer = new Serializer(
            $metadataFactory,
            $handlerRegistry,
            $objectConstructor,
            $serializationVisitor,
            $deserializationVisitor
        );
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $viewHandler = new ViewHandler($urlGenerator, $serializer, $engine, $requestStack, array('json' => true, 'html' => true));
        $viewHandler->registerHandler('json', array($viewHandler, 'createResponse'));
        $viewHandler->registerHandler('html', array($viewHandler, 'createResponse'));

        return $viewHandler;
    }

    /**
     * @return StateMachine
     */
    public static function getStateMachine(): StateMachine
    {
        $stateMachineFactory = new Factory(array());
        $stateMachine = new StateMachine($stateMachineFactory);

        return $stateMachine;
    }

    /**
     * @param ResourceControllerCommand $command
     *
     * @return ResourceFormFactory
     */
    public static function getResourceFormFactory(ResourceControllerCommand $command): ResourceFormFactory
    {
        $formFactory = self::getFormFactory($command);
        $resourceFormFactory = new ResourceFormFactory($formFactory);

        return $resourceFormFactory;
    }

    /**
     * @param StateMachine $stateMachine
     *
     * @return ResourceUpdateHandler
     */
    public static function getResourceUpdateHandler(StateMachine $stateMachine): ResourceUpdateHandler
    {
        $resourceUpdateHandler = new ResourceUpdateHandler($stateMachine);

        return $resourceUpdateHandler;
    }

    /**
     * @param ContainerInterface $container
     * @param string $resource
     *
     * @return RequestConfigurationFactory
     */
    public static function getRequestConfigurationFactory(ContainerInterface $container, $resource): RequestConfigurationFactory
    {
        $expressionLanguage = new ExpressionLanguage();
        $parameterParser = new ParametersParser($container, $expressionLanguage);
        $defaultParameters = array(
            'sorting'   => '$sorting',
            'page'      => '$page'
        );
        $requestConfigurationFactory = new RequestConfigurationFactory($parameterParser, RequestConfiguration::class, $defaultParameters);

        return $requestConfigurationFactory;
    }

    /**
     * @return SingleResourceProvider
     */
    public static function getSingleResourceProvider(): SingleResourceProvider
    {
        $singleResourceProvider = new SingleResourceProvider();

        return $singleResourceProvider;
    }

    /**
     * @param EntityManager $manager
     * @param string $resource
     *
     * @return EntityRepository
     */
    public static function getRepository(EntityManager $manager, $resource): EntityRepository
    {
        /** @var EntityRepository $repository */
        $repository = $manager->getRepository($resource);

        return $repository;
    }

    /**
     * @return ResourceDeleteHandler
     */
    public static function getResourceDeleteHandler(): ResourceDeleteHandler
    {
        $resourceDeleteHandler = new ResourceDeleteHandler();

        return $resourceDeleteHandler;
    }

    /**
     * @return ResourcesCollectionProvider
     */
    public static function getResourcesCollectionProvider(): ResourcesCollectionProvider
    {
        $resourceResolver = new ResourcesResolver();
        $pagerfantaFactory = new PagerfantaFactory();

        $resourcesCollectionProvider = new ResourcesCollectionProvider($resourceResolver, $pagerfantaFactory);

        return $resourcesCollectionProvider;
    }

    /**
     * @param ResourceControllerCommand $command
     * @param PermissionRepositoryInterface $permissionRepository
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param string $defaultLocale
     * @param Request $request
     * @param FileLocatorInterface $fileLocator
     * @param ContainerInterface $container
     * @param array $twigParams
     *
     * @return void
     *
     * @throws \Exception
     */
    public static function completeCommand(
        ResourceControllerCommand $command,
        PermissionRepositoryInterface $permissionRepository,
        RouterInterface $router,
        SessionInterface $session,
        TranslatorInterface $translator,
        $defaultLocale,
        Request $request,
        FileLocatorInterface $fileLocator,
        ContainerInterface $container,
        array $twigParams
    ): void
    {
        if(empty($command->metadata))
        {
            $command->metadata = self::getMetadata();
        }
        $command->authorizationChecker = self::getAuthorizationChecker($permissionRepository);
        $command->redirectHandler = self::getRedirectHandler($router);
        if(empty($command->eventDispatcher))
        {
            $command->eventDispatcher = self::getEventDispatcher();
        }
        $command->flashHelper = self::getFlashHelper($session, $translator, $defaultLocale);
        $engine = self::getEngine($twigParams, $command, $router);
        $command->viewHandler = self::getViewHandler($router, $fileLocator, $request, $engine);
        if(empty($command->stateMachine))
        {
            $command->stateMachine = self::getStateMachine();
        }
        if(empty($command->resourceUpdateHandler))
        {
            $command->resourceUpdateHandler = self::getResourceUpdateHandler($command->stateMachine);
        }
        if(empty($command->resourceDeleteHandler))
        {
            $command->resourceDeleteHandler = self::getResourceDeleteHandler();
        }
        if(empty($command->newResourceFactory))
        {
            throw new \Exception('Create resource service not found.');
        }
        if(empty($command->resource))
        {
            throw new \Exception('Resource class not found.');
        }
        if(empty($command->manager))
        {
            throw new \Exception('Entity manager service not found.');
        }
        if(empty($command->command))
        {
            throw new \Exception('Resource command not found.');
        }
        if(empty($command->resourceFormFactory))
        {
            $command->resourceFormFactory = self::getResourceFormFactory($command);
        }
        if(empty($command->repository))
        {
            $command->repository = self::getRepository($command->manager, $command->resource);
        }
        if(empty($command->resourcesCollectionProvider))
        {
            $command->resourcesCollectionProvider = self::getResourcesCollectionProvider();
        }
        if(empty($command->requestConfigurationFactory))
        {
            $command->requestConfigurationFactory = self::getRequestConfigurationFactory($container, $command->resource);
        }
        if(empty($command->singleResourceProvider))
        {
            $command->singleResourceProvider = self::getSingleResourceProvider();
        }
    }

    /**
     * @param ResourceControllerCommand $command
     * @param PermissionRepositoryInterface $permissionRepository
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     * @param string $defaultLocale
     * @param Request $request
     * @param FileLocatorInterface $fileLocator
     * @param ContainerInterface $container
     * @param array $twigParams
     *
     * @return ResourceController
     *
     * @throws
     */
    public static function create(
        ResourceControllerCommand $command,
        PermissionRepositoryInterface $permissionRepository,
        RouterInterface $router,
        SessionInterface $session,
        TranslatorInterface $translator,
        $defaultLocale,
        Request $request,
        FileLocatorInterface $fileLocator,
        ContainerInterface $container,
        array $twigParams
    ): ResourceController
    {
        self::setDefaultCommand($command);
        self::completeCommand(
            $command,
            $permissionRepository,
            $router,
            $session,
            $translator,
            $defaultLocale,
            $request,
            $fileLocator,
            $container,
            $twigParams
        );

        $resourceControllerClass = static::getResourceControllerClass();
        $resourceController = new $resourceControllerClass($command);

        return $resourceController;
    }
}