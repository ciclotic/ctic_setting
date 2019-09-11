<?php
namespace CTIC\App\Permission\Infrastructure\Repository;

use CTIC\App\Base\Infrastructure\Repository\PermissionRepositoryInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Permission\Domain\Permission;
use CTIC\App\User\Domain\User;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;
use Doctrine\ORM\EntityManager;

class PermissionRepository extends EntityRepository implements PermissionRepositoryInterface
{

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }

    /**
     * @param RequestConfiguration $configuration
     *
     * @return EntityInterface|Permission
     *
     * @throws
     */
    public function findPermissionByRequestConfiguration(RequestConfiguration $configuration): ?EntityInterface
    {


        $qb = $this->createQueryBuilder('p')
            ->where('p.route = ?1')
            ->setParameter(1, preg_replace('/(.*)?(_\d)/', '$1', $configuration->getRequest()->get('_route')))
            ->getQuery();


        /** @var Permission $permission */
        $permission = $qb->setMaxResults(1)->getOneOrNullResult();

        return $permission;
    }
}