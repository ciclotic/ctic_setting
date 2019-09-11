<?php

namespace CTIC\App\Base\Infrastructure\Repository;

use CTIC\App\Base\Domain\EntityInterface;

interface EntityRepositoryInterface
{
    public const ORDER_ASCENDING = 'ASC';
    public const ORDER_DESCENDING = 'DESC';

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return iterable
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable;

    /**
     * @param EntityInterface $resource
     */
    public function add(EntityInterface $resource): void;

    /**
     * @param EntityInterface $resource
     */
    public function remove(EntityInterface $resource): void;
}