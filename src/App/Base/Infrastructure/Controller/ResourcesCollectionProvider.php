<?php

namespace CTIC\App\Base\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Pagerfanta;
use CTIC\App\Base\Infrastructure\View\ResourceGridView;
use CTIC\App\Base\Infrastructure\Repository\EntityRepositoryInterface;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\ActionGroup;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;

final class ResourcesCollectionProvider implements ResourcesCollectionProviderInterface
{
    /**
     * @var ResourcesResolverInterface
     */
    private $resourcesResolver;

    /**
     * @var PagerfantaFactory
     */
    private $pagerfantaRepresentationFactory;

    /**
     * @param ResourcesResolverInterface $resourcesResolver
     * @param PagerfantaFactory $pagerfantaRepresentationFactory
     */
    public function __construct(ResourcesResolverInterface $resourcesResolver, PagerfantaFactory $pagerfantaRepresentationFactory)
    {
        $this->resourcesResolver = $resourcesResolver;
        $this->pagerfantaRepresentationFactory = $pagerfantaRepresentationFactory;
    }

    /**
     * @param Grid $grid
     * @param Parameters|null $parameters
     *
     * @return bool
     */
    protected function getResourceGridViewFilters( Grid $grid, Parameters $parameters = null): bool
    {
        if ($parameters === null || !$parameters->has('filters')) {
            return false;
        }

        $filters = $parameters->get('filters');
        if (!is_array($filters)) {
            return false;
        }
        foreach ($filters as $key => $filter) {
            if (!isset($filter['name']) || !isset($filter['type'])) {
                continue;
            }
            $filterToAdd = Filter::fromNameAndType($filter['name'], $filter['type']);
            if (!empty($filter['enabled'])) {
                $filterToAdd->setEnabled($filter['enabled']);
            }
            if (!empty($filter['label'])) {
                $filterToAdd->setLabel($filter['label']);
            }
            if (!empty($filter['position'])) {
                $filterToAdd->setPosition($filter['position']);
            }
            if (!empty($filter['formOptions'])) {
                $filterToAdd->setFormOptions($filter['formOptions']);
            }
            $grid->addFilter($filterToAdd);
        }

        return true;
    }

    /**
     * @param Grid $grid
     * @param Parameters|null $parameters
     * @param Pagerfanta|null $paginator
     *
     * @return bool
     */
    protected function getResourceGridViewPaginator( Grid $grid, Parameters $parameters = null, $paginator = null): bool
    {
        if ($parameters === null || $paginator === null) {
            return false;
        }

        $driverConfiguration = $grid->getDriverConfiguration();

        $driverConfiguration['paginator'] = array();
        $driverConfiguration['paginator']['currentPage'] = $paginator->getCurrentPage();
        $driverConfiguration['paginator']['nbPages'] = $paginator->getNbPages();

        $grid->setDriverConfiguration($driverConfiguration);

        return true;
    }

    /**
     * @param Grid $grid
     * @param Parameters|null $parameters
     *
     * @return bool
     */
    protected function getResourceGridViewActions( Grid $grid, Parameters $parameters = null): bool
    {
        if ($parameters === null || !$parameters->has('actionGroups')) {
            return false;
        }

        $actionGroups = $parameters->get('actionGroups');
        if(!is_array($actionGroups))
        {
            return false;
        }
        foreach ($actionGroups as $actionGroupName => $actions) {
            if(!is_array($actions))
            {
                continue;
            }
            $actionGroupToAdd = ActionGroup::named($actionGroupName);
            foreach ($actions as $key => $action) {
                if (!isset($action['name']) || !isset($action['type'])) {
                    continue;
                }
                $actionToAdd = Action::fromNameAndType($action['name'], $action['type']);
                if (!empty($action['enabled'])) {
                    $actionToAdd->setEnabled($action['enabled']);
                }
                if (!empty($action['label'])) {
                    $actionToAdd->setLabel($action['label']);
                }
                if (!empty($action['position'])) {
                    $actionToAdd->setPosition($action['position']);
                }
                $actionGroupToAdd->addAction($actionToAdd);
            }
            $grid->addActionGroup($actionGroupToAdd);
        }

        return true;
    }

    /**
     * @param Grid $grid
     * @param Parameters|null $parameters
     *
     * @return bool
     */
    protected function getResourceGridViewFields( Grid $grid, Parameters $parameters = null): bool
    {
        if ($parameters === null || !$parameters->has('fields')) {
            return false;
        }

        $fields = $parameters->get('fields');
        if (!is_array($fields)) {
            return false;
        }
        foreach ($fields as $key => $field) {
            if (!isset($field['name']) || !isset($field['type'])) {
                continue;
            }
            $fieldToAdd = Field::fromNameAndType($field['name'], $field['type']);
            if (!empty($field['enabled'])) {
                $fieldToAdd->setEnabled($field['enabled']);
            }
            if (!empty($field['isSortable'])) {
                $fieldToAdd->setSortable($field['isSortable']);
            }
            if (!empty($field['label'])) {
                $fieldToAdd->setLabel($field['label']);
            }
            if (!empty($field['position'])) {
                $fieldToAdd->setPosition($field['position']);
            }
            $grid->addField($fieldToAdd);
        }

        return true;
    }

    /**
     * @param $resources
     * @param RequestConfiguration $requestConfiguration
     * @param Parameters|null $parameters
     * @param Pagerfanta|null $paginator
     *
     * @return ResourceGridView
     */
    protected function getResourceGridView($resources, RequestConfiguration $requestConfiguration, Parameters $parameters = null, $paginator = null)
    {
        $data = $resources;
        $grid = Grid::fromCodeAndDriverConfiguration('list', '', array());

        if ($parameters->has('sorting')) {
            $grid->setSorting($parameters->get('sorting'));
        }

        if ($parameters->has('limit')) {
            $limit = $parameters->get('limit');
        } else {
            $limit = 10;
        }
        $grid->setLimits(array($limit));

        $this->getResourceGridViewFields($grid, $parameters);
        $this->getResourceGridViewFilters($grid, $parameters);
        $this->getResourceGridViewActions($grid, $parameters);
        $this->getResourceGridViewPaginator($grid, $parameters, $paginator);

        return new ResourceGridView($data, $grid, $parameters, $requestConfiguration->getMetadata(), $requestConfiguration);
    }

    /**
     * {@inheritdoc}
     */
    public function get(RequestConfiguration $requestConfiguration, EntityRepositoryInterface $repository, Parameters $parameters = null)
    {
        $resources = $this->resourcesResolver->getResources($requestConfiguration, $repository);
        $paginationLimits = [];

        if(is_array($resources) && $parameters !== null)
        {
            $resources = $this->getResourceGridView($resources, $requestConfiguration, $parameters);
        }

        if ($resources instanceof ResourceGridView) {
            $paginator = $resources->getData();
            $paginationLimits = $resources->getDefinition()->getLimits();
        } else {
            $paginator = $resources;
        }

        if ($paginator instanceof Pagerfanta) {
            $request = $requestConfiguration->getRequest();

            $paginator->setMaxPerPage($this->resolveMaxPerPage(
                (!empty($parameters) && $parameters->has('limit')) ? $parameters->get('limit') : null,
                $requestConfiguration->getPaginationMaxPerPage(),
                $paginationLimits
            ));
            $paginator->setCurrentPage($request->query->get('page', 1));

            // This prevents Pagerfanta from querying database from a template
            $resources = (array) $paginator->getCurrentPageResults();

            if (!$requestConfiguration->isHtmlRequest()) {
                $route = new Route($request->attributes->get('_route'), array_merge($request->attributes->get('_route_params'), $request->query->all()));

                return $this->pagerfantaRepresentationFactory->createRepresentation($paginator, $route);
            }

            if(is_array($resources) && $parameters !== null)
            {
                $resources = $this->getResourceGridView($resources, $requestConfiguration, $parameters, $paginator);
            }
        }

        return $resources;
    }

    /**
     * @param int|null $requestLimit
     * @param int $configurationLimit
     * @param int[] $gridLimits
     *
     * @return int
     */
    private function resolveMaxPerPage(?int $requestLimit, int $configurationLimit, array $gridLimits = []): int
    {
        if (null === $requestLimit) {
            return reset($gridLimits) ?: $configurationLimit;
        }

        if (!empty($gridLimits)) {
            $maxGridLimit = max($gridLimits);

            return $requestLimit > $maxGridLimit ? $maxGridLimit : $requestLimit;
        }

        return $requestLimit;
    }
}
