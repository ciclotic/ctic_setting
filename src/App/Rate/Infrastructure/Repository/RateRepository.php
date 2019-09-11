<?php
namespace CTIC\App\Rate\Infrastructure\Repository;

use CTIC\App\Rate\Domain\Rate;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class RateRepository extends EntityRepository
{
    /**
     * @return Rate[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Rate
     *
     * @throws
     */
    public function findOneRandom(): Rate
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        /** @var Rate $rate */
        $rate = $qb->setMaxResults(1)->getOneOrNullResult();

        return $rate;
    }
}