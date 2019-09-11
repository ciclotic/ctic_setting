<?php

namespace CTIC\App\Base\Infrastructure\View;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridViewInterface;
use Webmozart\Assert\Assert;

class GridView implements GridViewInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var Grid
     */
    protected $definition;

    /**
     * @var Parameters
     */
    protected $parameters;

    /**
     * @param mixed $data
     * @param Grid $definition
     * @param Parameters $parameters
     */
    public function __construct($data, Grid $definition, Parameters $parameters)
    {
        $this->data = $data;
        $this->definition = $definition;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): Grid
    {
        return $this->definition;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): Parameters
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getSortingOrder(string $fieldName): ?string
    {
        $this->assertFieldIsSortable($fieldName);

        $currentSorting = $this->getCurrentlySortedBy();

        if (array_key_exists($fieldName, $currentSorting)) {
            return $currentSorting[$fieldName];
        }

        $definedSorting = $this->definition->getSorting();

        return reset($definedSorting) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSortedBy(string $fieldName): bool
    {
        $this->assertFieldIsSortable($fieldName);

        if ($this->parameters->has('sorting')) {
            $sorting = $this->parameters->get('sorting');
            if(!empty($sorting))
            {
                return array_key_exists($fieldName, $sorting);
            }
        }

        $sortingDefinition = $this->getDefinition()->getSorting();
        $sortedFields = array_keys($sortingDefinition);

        return $fieldName === array_shift($sortedFields);
    }

    /**
     * @return array
     */
    protected function getCurrentlySortedBy(): array
    {
        return $this->parameters->has('sorting')
            ? array_merge($this->definition->getSorting(), $this->parameters->get('sorting'))
            : $this->definition->getSorting()
        ;
    }

    /**
     * @param string $fieldName
     *
     * @throws \InvalidArgumentException
     */
    protected function assertFieldIsSortable(string $fieldName): void
    {
        Assert::true($this->definition->hasField($fieldName), sprintf('Field "%s" does not exist.', $fieldName));
        Assert::true(
            $this->definition->getField($fieldName)->isSortable(),
            sprintf('Field "%s" is not sortable.', $fieldName)
        );
    }
}
