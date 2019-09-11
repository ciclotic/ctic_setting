<?php
namespace CTIC\App\User\Infrastructure\Repository;

use CTIC\App\User\Domain\User;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return User[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return User
     *
     * @throws
     */
    public function findOneRandom(): User
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')
            ->getQuery();

        /** @var User $user */
        $user = $qb->setMaxResults(1)->getOneOrNullResult();

        return $user;
    }
}