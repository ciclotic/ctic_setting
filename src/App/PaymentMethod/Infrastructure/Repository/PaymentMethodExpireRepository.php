<?php
namespace CTIC\App\PaymentMethod\Infrastructure\Repository;

use CTIC\App\PaymentMethod\Domain\PaymentMethod;
use CTIC\App\Base\Infrastructure\Repository\EntityRepository;

class PaymentMethodExpireRepository extends EntityRepository
{
    /**
     * @return PaymentMethod[]
     */
    public function findAllOrderedByName(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @return PaymentMethod
     *
     * @throws
     */
    public function findOneRandom(): PaymentMethod
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery();

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $qb->setMaxResults(1)->getOneOrNullResult();

        return $paymentMethod;
    }
}