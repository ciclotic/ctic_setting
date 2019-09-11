<?php
namespace CTIC\App\Privacy\Infrastructure\Repository;

use CTIC\App\Privacy\Domain\Privacy;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class PrivacyRepository extends EntityRepository
{
    /**
     * @return Privacy[]
     */
    public function findAllRoot(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.root', true)
            ->getQuery();

        return $qb->execute();
    }
}