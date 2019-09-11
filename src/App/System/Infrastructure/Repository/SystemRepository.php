<?php
namespace CTIC\App\System\Infrastructure\Repository;

use CTIC\App\System\Domain\System;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class SystemRepository extends EntityRepository
{
    /**
     * @return System[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return System
     *
     * @throws
     */
    public function findOneRandom(): System
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.name', 'ASC')
            ->getQuery();

        /** @var System $system */
        $system = $qb->setMaxResults(1)->getOneOrNullResult();

        return $system;
    }
}