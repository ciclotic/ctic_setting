<?php

namespace CTIC\App\Base\Application;

use CTIC\App\Base\Domain\BasePermissionInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Base\Infrastructure\Repository\PermissionRepositoryInterface;
use CTIC\App\User\Domain\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthorizationChecker implements AuthorizationCheckerInterface
{

    /**
     * @var PermissionRepositoryInterface
     */
    private $repository;

    /**
     * @param PermissionRepositoryInterface $repository
     */
    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RequestConfiguration $configuration
     *
     * @return bool
     */
    public function isGranted(RequestConfiguration $configuration): bool
    {
        $session = new Session();
        if (empty($session)) {
            return false;
        }
        $configuration->getRequest()->setSession($session);

        $identity = $session->get('identity');
        if (empty($identity)) {
            return false;
        }
        if (!empty($identity['data']['username'])) {
            $configuration->getRequest()->headers->set('PHP_AUTH_USER', $identity['data']['username']);
        }

        $identityPermissions = $identity['roles'];
        if (is_array($identityPermissions)) {
            $identityPermissions = array_pop($identityPermissions);
        }

        if ($identityPermissions == BasePermissionInterface::TYPE_ADMINISTRATOR) {
            return true;
        }

        $permission = $this->repository->findPermissionByRequestConfiguration($configuration);

        if (is_object($permission) && $permission->getType() == BasePermissionInterface::TYPE_ANONYMOUS) {
            return true;
        }

        $permissionType = (is_object($permission))? $permission->getType() : null;

        if (empty($permissionType)) {
            return false;
        }

        if ($permissionType == $identityPermissions) {
            return true;
        }

        if ($identityPermissions == BasePermissionInterface::TYPE_USER &&
            $permissionType == BasePermissionInterface::TYPE_EMPLOYEE
        ) {
            return true;
        }

        return false;
    }
}