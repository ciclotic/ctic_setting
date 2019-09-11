<?php
namespace CTIC\App\Account\Infrastructure\Repository;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class AccountRepository extends EntityRepository
{
    /**
     * @return Account[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
}