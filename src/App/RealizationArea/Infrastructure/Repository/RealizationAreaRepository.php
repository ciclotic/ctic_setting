<?php
namespace CTIC\App\RealizationArea\Infrastructure\Repository;

use CTIC\App\RealizationArea\Domain\RealizationArea;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class RealizationAreaRepository extends EntityRepository
{
    /**
     * @return RealizationArea[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return RealizationArea
     *
     * @throws
     */
    public function findOneRandom(): RealizationArea
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        /** @var RealizationArea $realizationArea */
        $realizationArea = $qb->setMaxResults(1)->getOneOrNullResult();

        return $realizationArea;
    }
}