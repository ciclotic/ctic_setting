<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;

final class ResourcesResolver implements ResourcesResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getResources(RequestConfiguration $requestConfiguration, EntityRepositoryInterface $repository)
    {
        $repositoryMethod = $requestConfiguration->getRepositoryMethod();
        if (null !== $repositoryMethod) {
            $arguments = array_values($requestConfiguration->getRepositoryArguments());

            return $repository->$repositoryMethod(...$arguments);
        }

        $criteria = [];
        if ($requestConfiguration->isFilterable()) {
            $criteriaParams = array();
            if($requestConfiguration->getMetadata()->hasParameter('grid'))
            {
                $grid = $requestConfiguration->getMetadata()->getParameter('grid');
                if(!empty($grid['filters']) && is_array($grid['filters']))
                {
                    foreach($grid['filters'] as $filter)
                    {
                        if(!empty($filter['enabled']) && !empty($filter['name']))
                        {
                            $criteriaParams[] = $filter['name'];
                        }
                    }
                }
            }
            $criteria = $requestConfiguration->getCriteria($criteriaParams);
        }

        $sorting = [];
        if ($requestConfiguration->isSortable()) {
            $sorting = $requestConfiguration->getSorting();
        }

        if ($requestConfiguration->isPaginated()) {
            return $repository->createPaginator($criteria, $sorting);
        }

        return $repository->findBy($criteria, $sorting, $requestConfiguration->getLimit());
    }
}
