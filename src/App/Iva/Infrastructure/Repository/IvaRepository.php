<?php
namespace CTIC\App\Iva\Infrastructure\Repository;

use CTIC\App\Iva\Domain\Iva;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class IvaRepository extends EntityRepository
{
    /**
     * @return Iva[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Iva
     *
     * @throws
     */
    public function findOneRandom(): Iva
    {
        $qb = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC')
            ->getQuery();

        /** @var Iva $iva */
        $iva = $qb->setMaxResults(1)->getOneOrNullResult();

        return $iva;
    }
}