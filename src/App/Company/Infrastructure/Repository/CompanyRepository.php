<?php
namespace CTIC\App\Company\Infrastructure\Repository;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class CompanyRepository extends EntityRepository
{
    /**
     * @return Company[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Company
     *
     * @throws
     */
    public function findOneRandom(): Company
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery();

        /** @var Company $company */
        $company = $qb->setMaxResults(1)->getOneOrNullResult();

        return $company;
    }
}