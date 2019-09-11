<?php
namespace CTIC\App\Irpf\Infrastructure\Repository;

use CTIC\App\Irpf\Domain\Irpf;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class IrpfRepository extends EntityRepository
{
    /**
     * @return Irpf[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return Irpf
     *
     * @throws
     */
    public function findOneRandom(): Irpf
    {
        $qb = $this->createQueryBuilder('i')
            ->orderBy('i.name', 'ASC')
            ->getQuery();

        /** @var Irpf $irpf */
        $irpf = $qb->setMaxResults(1)->getOneOrNullResult();

        return $irpf;
    }
}