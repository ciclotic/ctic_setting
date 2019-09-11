<?php

namespace CTIC\App\Base\Infrastructure\View;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use CTIC\App\Base\Domain\MetadataInterface;

class ResourceGridView extends GridView
{
    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var RequestConfiguration
     */
    private $requestConfiguration;

    /**
     * @param mixed $data
     * @param Grid $gridDefinition
     * @param Parameters $parameters
     * @param MetadataInterface $resourceMetadata
     * @param RequestConfiguration $requestConfiguration
     */
    public function __construct(
        $data,
        Grid $gridDefinition,
        Parameters $parameters,
        MetadataInterface $resourceMetadata,
        RequestConfiguration $requestConfiguration
    ) {
        parent::__construct($data, $gridDefinition, $parameters);

        $this->metadata = $resourceMetadata;
        $this->requestConfiguration = $requestConfiguration;
    }

    /**
     * @return MetadataInterface
     */
    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @return RequestConfiguration
     */
    public function getRequestConfiguration(): RequestConfiguration
    {
        return $this->requestConfiguration;
    }
}
