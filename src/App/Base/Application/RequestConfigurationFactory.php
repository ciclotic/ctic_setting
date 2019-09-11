<?php

namespace CTIC\App\Base\Application;

use CTIC\App\Base\Domain\MetadataInterface;
use CTIC\App\Base\Infrastructure\Request\Parameters;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Symfony\Component\HttpFoundation\Request;

final class RequestConfigurationFactory implements RequestConfigurationFactoryInterface
{
    private const API_VERSION_HEADER = 'Accept';
    private const API_GROUPS_HEADER = 'Accept';

    private const API_VERSION_REGEXP = '/(v|version)=(?P<version>[0-9\.]+)/i';
    private const API_GROUPS_REGEXP = '/(g|groups)=(?P<groups>[a-z,_\s]+)/i';

    /**
     * @var ParametersParserInterface
     */
    private $parametersParser;

    /**
     * @var string
     */
    private $configurationClass;

    /**
     * @var array
     */
    private $defaultParameters;

    /**
     * @param ParametersParserInterface $parametersParser
     * @param string $configurationClass
     * @param array $defaultParameters
     */
    public function __construct(ParametersParserInterface $parametersParser, string $configurationClass, array $defaultParameters = [])
    {
        $this->parametersParser = $parametersParser;
        $this->configurationClass = $configurationClass;
        $this->defaultParameters = $defaultParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function create(MetadataInterface $metadata, Request $request): RequestConfiguration
    {
        $parameters = array_merge($this->defaultParameters, $this->parseApiParameters($request));
        $parameters = $this->parametersParser->parseRequestValues($parameters, $request);

        $gridMetadataParameters = $metadata->getParameter('grid');

        foreach ($parameters as $key => $value) {
            if ($value === null && !empty($gridMetadataParameters[$key])) {
                $parameters[$key] = $gridMetadataParameters[$key];
            }
        }

        if ($parameters !== null && $request->query->has('sorting')) {
            $sorting = $request->query->get('sorting');
            if ($sorting !== null)
            {
                $parameters['sorting'] = $sorting;
            }
        }

        if ($request->query->has('page')) {
            $page = $request->query->get('page');
        } else {
            $page = 1;
        }
        $parameters['page'] = $page;
        if ($request->query->has('limit')) {
            $limit = $request->query->get('limit');
        } else {
            if (empty($parameters['limit'])) {
                $limit = 10;
            } else {
                $parameters['limit'];
            }
        }
        $parameters['limit'] = $limit;

        return new $this->configurationClass($metadata, $request, new Parameters($parameters));
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function parseApiParameters(Request $request): array
    {
        $parameters = [];

        $apiVersionHeaders = $request->headers->get(self::API_VERSION_HEADER, null, false);
        foreach ($apiVersionHeaders as $apiVersionHeader) {
            if (preg_match(self::API_VERSION_REGEXP, $apiVersionHeader, $matches)) {
                $parameters['serialization_version'] = $matches['version'];
            }
        }

        $apiGroupsHeaders = $request->headers->get(self::API_GROUPS_HEADER, null, false);
        foreach ($apiGroupsHeaders as $apiGroupsHeader) {
            if (preg_match(self::API_GROUPS_REGEXP, $apiGroupsHeader, $matches)) {
                $parameters['serialization_groups'] = array_map('trim', explode(',', $matches['groups']));
            }
        }

        return $parameters;
    }
}
