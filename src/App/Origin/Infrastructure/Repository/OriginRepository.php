<?php
namespace CTIC\App\Origin\Infrastructure\Repository;

use CTIC\App\Origin\Domain\Origin;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class OriginRepository extends EntityRepository
{
    /**
     * @return Origin[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Origin
     *
     * @throws
     */
    public function findOneRandom(): Origin
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        /** @var Origin $origin */
        $origin = $qb->setMaxResults(1)->getOneOrNullResult();

        return $origin;
    }
}