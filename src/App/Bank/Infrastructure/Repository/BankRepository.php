<?php
namespace CTIC\App\Bank\Infrastructure\Repository;

use CTIC\App\Bank\Domain\Bank;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class BankRepository extends EntityRepository
{
    /**
     * @return Bank[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Bank
     *
     * @throws
     */
    public function findOneRandom(): Bank
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.name', 'ASC')
            ->getQuery();

        /** @var Bank $bank */
        $bank = $qb->setMaxResults(1)->getOneOrNullResult();

        return $bank;
    }
}