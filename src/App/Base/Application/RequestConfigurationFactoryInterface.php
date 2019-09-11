<?php

namespace CTIC\App\Base\Application;

use CTIC\App\Base\Domain\MetadataInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Symfony\Component\HttpFoundation\Request;

interface RequestConfigurationFactoryInterface
{
    /**
     * @param MetadataInterface $metadata
     * @param Request $request
     *
     * @return RequestConfiguration
     *
     * @throws \InvalidArgumentException
     */
    public function create(MetadataInterface $metadata, Request $request): RequestConfiguration;
}
