<?php
namespace CTIC\App\Base\Infrastructure\Controller\Command;

use CTIC\App\Base\Application\AuthorizationCheckerInterface;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Application\RequestConfigurationFactoryInterface;
use CTIC\App\Base\Application\SingleResourceProviderInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\MetadataInterface;
use CTIC\App\Base\Infrastructure\Controller\FlashHelperInterface;
use CTIC\App\Base\Infrastructure\Controller\RedirectHandlerInterface;
use CTIC\App\Base\Infrastructure\Controller\ResourceDeleteHandlerInterface;
use CTIC\App\Base\Infrastructure\Controller\ResourcesCollectionProviderInterface;
use CTIC\App\Base\Infrastructure\Controller\ResourceUpdateHandlerInterface;
use CTIC\App\Base\Infrastructure\Controller\StateMachineInterface;
use CTIC\App\Base\Infrastructure\Event\EventDispatcherInterface;
use CTIC\App\Base\Infrastructure\Form\ResourceFormFactoryInterface;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;
use CTIC\App\Base\Infrastructure\View\Rest\ViewHandlerInterface;
use Doctrine\ORM\EntityManager;

class ResourceControllerCommand
{
    /**
     * @var EntityInterface
     */
    public $resource;

    /**
     * @var CommandInterface
     */
    public $command;

    /**
     * @var MetadataInterface
     */
    public $metadata;

    /**
     * @var AuthorizationCheckerInterface
     */
    public $authorizationChecker;

    /**
     * @var RedirectHandlerInterface
     */
    public $redirectHandler;

    /**
     * @var EventDispatcherInterface
     */
    public $eventDispatcher;

    /**
     * @var FlashHelperInterface
     */
    public $flashHelper;

    /**
     * @var ViewHandlerInterface
     */
    public $viewHandler;

    /**
     * @var ResourceUpdateHandlerInterface
     */
    public $resourceUpdateHandler;

    /**
     * @var ResourceDeleteHandlerInterface
     */
    public $resourceDeleteHandler;

    /**
     * @var StateMachineInterface
     */
    public $stateMachine;

    /**
     * @var ResourceFormFactoryInterface
     */
    public $resourceFormFactory;

    /**
     * @var CreateInterface
     */
    public $newResourceFactory;

    /**
     * @var ResourcesCollectionProviderInterface
     */
    public $resourcesCollectionProvider;

    /**
     * @var RequestConfigurationFactoryInterface
     */
    public $requestConfigurationFactory;

    /**
     * @var SingleResourceProviderInterface
     */
    public $singleResourceProvider;

    /**
     * @var EntityRepository
     */
    public $repository;

    /**
     * @var CreateInterface
     */
    public $factory;

    /**
     * @var EntityManager
     */
    public $manager;
}