<?php
namespace CTIC\App\Company\Application;

use CTIC\App\Base\Application\CreateResourceController;
use CTIC\App\Base\Domain\Metadata;
use CTIC\App\Base\Infrastructure\Controller\Command\ResourceControllerCommand;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\Base\Infrastructure\Repository\PermissionRepositoryInterface;
use CTIC\App\Company\Domain\Command\CompanyCommand;
use CTIC\App\Company\Domain\Company;
use CTIC\App\Company\Infrastructure\Controller\CompanyController;
use CTIC\App\Company\Infrastructure\Form\Type\CompanyType;
use Metadata\Driver\FileLocatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CTIC\App\Base\Infrastructure\Form\FormRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CreateCompanyController extends CreateResourceController
{
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
        parent::completeFormRegistry($formRegistry, $command);

        $formRegistry->addType(new CompanyType(Company::class));
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

        if(empty($command->repository))
        {
            $command->repository = self::getRepository($command->manager, $command->resource);
        }
        if(empty($command->metadata))
        {
            $command->metadata = self::getMetadata(
                'Company',
                'App',
                array(
                    'permission'    => true,
                    'repository'    => $command->repository,
                    'driver'        => '',
                    'templates'     => '',
                    'header'        => 'empresas',
                    'route'         => array(
                        'root'      => 'empresas',
                        'actions'   => array(
                            'list'  => 'listado',
                            'show'  => 'mostrar',
                            'create'    =>'crear',
                            'update'    =>'modificar',
                            'delete'    =>'eliminar',
                            'bulkDelete'    => 'eliminargrupo',
                            'download'    => 'descargar'
                        )
                    ),
                    'classes'       => array(
                        'form'      => 'CTIC\App\Company\Infrastructure\Form\Type\CompanyType'
                    ),
                    'grid'          => array(
                        'actionGroups'  => array(
                            'bulk'  => array(
                                'delete'  => array(
                                    'type'      => 'delete',
                                    'enabled'   => true,
                                    'position'  => 10,
                                    'name'      => 'delete',
                                    'label'     => 'Eliminar'
                                ),
                                'download'  => array(
                                    'type'      => 'download',
                                    'enabled'   => true,
                                    'position'  => 20,
                                    'name'      => 'download',
                                    'label'     => 'Descargar'
                                )
                            ),
                            'item'  => array(
                                'show'  => array(
                                    'type'      => 'show',
                                    'enabled'   => true,
                                    'position'  => 10,
                                    'name'      => 'show',
                                    'label'     => 'Mostrar'
                                ),
                                'edit'  => array(
                                    'type'      => 'edit',
                                    'enabled'   => true,
                                    'position'  => 20,
                                    'name'      => 'edit',
                                    'label'     => 'Editar'
                                ),
                                'delete'  => array(
                                    'type'      => 'delete',
                                    'enabled'   => true,
                                    'position'  => 30,
                                    'name'      => 'delete',
                                    'label'     => 'Eliminar'
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
                            'taxName'    => array(
                                'type'          => 'text',
                                'isSortable'    => true,
                                'name'          => 'taxName',
                                'label'         => 'Nombre fiscal',
                                'position'      => '20'
                            ),
                            'businessName'    => array(
                                'type'          => 'text',
                                'isSortable'    => true,
                                'name'          => 'businessName',
                                'label'         => 'Nombre comercial',
                                'position'      => '25'
                            ),
                            'enabled'    => array(
                                'type'          => 'enabled',
                                'isSortable'    => true,
                                'name'          => 'enabled',
                                'label'         => 'Habilitado',
                                'position'      => '30'
                            )
                        ),
                        'filterable' => true,
                        'filters'    => array(
                            'id'    => array(
                                'type'          => 'text',
                                'enabled'       => true,
                                'name'          => 'id',
                                'label'         => 'Id',
                                'formOptions'   => array(),
                                'position'      => '10'
                            ),
                            'taxName'    => array(
                                'type'          => 'text',
                                'enabled'       => true,
                                'name'          => 'taxName',
                                'label'         => 'Nombre fiscal',
                                'formOptions'   => array(),
                                'position'      => '20'
                            ),
                            'businessName'    => array(
                                'type'          => 'text',
                                'enabled'       => true,
                                'name'          => 'businessName',
                                'label'         => 'Nombre comercial',
                                'formOptions'   => array(),
                                'position'      => '25'
                            ),
                            'enabled'    => array(
                                'type'          => 'choice',
                                'enabled'       => true,
                                'name'          => 'enabled',
                                'label'         => 'Habilitado',
                                'formOptions'   => array(
                                    'choices'   => array(
                                        'Si / No'    => '',
                                        'Si'    => 1,
                                        'No'    => 0
                                    )
                                ),
                                'position'      => '30'
                            )
                        )
                    )
                )
            );
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

    public static function setDefaultCommand(ResourceControllerCommand $command): void
    {
        if(empty($command->newResourceFactory))
        {
            $command->newResourceFactory = new CreateCompany();
        }
        if(empty($command->resource))
        {
            $command->resource = Company::class;
        }
        if(empty($command->command))
        {
            $command->command = new CompanyCommand();
        }
    }

    public static function getResourceControllerClass(): string
    {
        return CompanyController::class;
    }

    /**
     * @inheritdoc
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