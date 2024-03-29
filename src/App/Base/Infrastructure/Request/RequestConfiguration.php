<?php

namespace CTIC\App\Base\Infrastructure\Request;

use CTIC\App\Base\Domain\MetadataInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RequestConfiguration
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var MetadataInterface
     */
    private $metadata;

    /**
     * @var Parameters
     */
    private $parameters;

    /**
     * @param MetadataInterface $metadata
     * @param Request $request
     * @param Parameters $parameters
     */
    public function __construct(MetadataInterface $metadata, Request $request, Parameters $parameters)
    {
        $this->metadata = $metadata;
        $this->request = $request;
        $this->parameters = $parameters;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return MetadataInterface
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return Parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getSection()
    {
        return $this->parameters->get('section');
    }

    /**
     * @return bool
     */
    public function isHtmlRequest()
    {
        return 'html' === $this->request->getRequestFormat();
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getDefaultTemplate($name)
    {
        $templatesNamespace = (string) $this->metadata->getTemplatesNamespace();

        if (false !== strpos($templatesNamespace, ':')) {
            return sprintf('%s:%s.%s', $templatesNamespace ?: ':', $name, 'twig');
        }

        return sprintf('%s/%s.%s', $templatesNamespace, $name, 'twig');
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getTemplate($name)
    {
        $template = $this->parameters->get('template', $this->getDefaultTemplate($name));

        if (null === $template) {
            throw new \RuntimeException(sprintf('Could not resolve template for resource "%s".', $this->metadata->getAlias()));
        }

        return $template;
    }

    /**
     * @return string|null
     */
    public function getFormType()
    {
        $form = $this->parameters->get('form');
        if (isset($form['type'])) {
            return $form['type'];
        }

        if (is_string($form)) {
            return $form;
        }

        $form = $this->metadata->getClass('form');
        if (is_string($form)) {
            return $form;
        }

        return sprintf('%s_%s', $this->metadata->getApplicationName(), $this->metadata->getName());
    }

    /**
     * @return array
     */
    public function getFormOptions()
    {
        $form = $this->parameters->get('form');
        if (isset($form['options'])) {
            return $form['options'];
        }

        return [];
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getRouteName($name = 'list')
    {
        $routeParams = $this->metadata->getParameter('route');
        return sprintf('%s_%s_%s', 'GET', $routeParams['root'], $routeParams['actions'][$name]);
    }

    /**
     * @param string $name
     *
     * @return mixed|string|null
     */
    public function getRedirectRoute($name)
    {
        $redirect = $this->parameters->get('redirect');

        if (null === $redirect) {
            return $this->getRouteName($name);
        }

        if (is_array($redirect)) {
            if (!empty($redirect['referer'])) {
                return 'referer';
            }

            return $redirect['route'];
        }

        return $redirect;
    }

    /**
     * Get url hash fragment (#text) which is you configured.
     *
     * @return string
     */
    public function getRedirectHash()
    {
        $redirect = $this->parameters->get('redirect');

        if (!is_array($redirect) || empty($redirect['hash'])) {
            return '';
        }

        return '#' . $redirect['hash'];
    }

    /**
     * Get redirect referer, This will detected by configuration
     * If not exists, The `referrer` from headers will be used.
     *
     * @return string
     */
    public function getRedirectReferer()
    {
        $redirect = $this->parameters->get('redirect');
        $referer = $this->request->headers->get('referer');

        if (!is_array($redirect) || empty($redirect['referer'])) {
            return $referer;
        }

        if ($redirect['referer'] === true) {
            return $referer;
        }

        return $redirect['referer'];
    }

    /**
     * @param object|null $resource
     *
     * @return array
     */
    public function getRedirectParameters($resource = null)
    {
        $redirect = $this->parameters->get('redirect');

        if ($this->areParametersIntentionallyEmptyArray($redirect)) {
            return [];
        }

        if (!is_array($redirect)) {
            $redirect = ['parameters' => []];
        }

        $parameters = $redirect['parameters'] ?? [];
        $parameters = $this->addExtraRedirectParameters($parameters);

        if (null !== $resource) {
            $parameters = $this->parseResourceValues($parameters, $resource);
        }

        return $parameters;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function addExtraRedirectParameters($parameters): array
    {
        $vars = $this->getVars();
        $accessor = PropertyAccess::createPropertyAccessor();

        if ($accessor->isReadable($vars, '[redirect][parameters]')) {
            $extraParameters = $accessor->getValue($vars, '[redirect][parameters]');

            if (is_array($extraParameters)) {
                $parameters = array_merge($parameters, $extraParameters);
            }
        }

        return $parameters;
    }

    /**
     * @return bool
     */
    public function isLimited()
    {
        $grid = $this->metadata->getParameter('grid');
        return (bool) (empty($grid['limit']))? false : $grid['limit'];
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        $limit = null;

        if ($this->isLimited()) {
            $grid = $this->metadata->getParameter('grid');
            $limit = (int) (empty($grid['limit']))? 10 : $grid['limit'];
        }

        return $limit;
    }

    /**
     * @return bool
     */
    public function isPaginated()
    {
        $grid = $this->metadata->getParameter('grid');
        return (bool) (empty($grid['paginate']))? false : $grid['paginate'];
    }

    /**
     * @return int
     */
    public function getPaginationMaxPerPage()
    {
        $grid = $this->metadata->getParameter('grid');
        return (bool) (empty($grid['paginate']))? 10 : $grid['paginate'];
    }

    /**
     * @return bool
     */
    public function isFilterable()
    {
        $grid = $this->metadata->getParameter('grid');
        return (bool) (empty($grid['filterable']))? false : $grid['filterable'];
    }

    /**
     * @param array $criteria
     * @param array $defaultCriteria
     *
     * @return array
     */
    public function getCriteria(array $criteria = [], array $defaultCriteria = [])
    {
        $defaultCriteria = array_merge($this->parameters->get('criteria', []), $defaultCriteria);

        if ($this->isFilterable()) {
            $requestParameter = array();
            foreach($criteria as $filter)
            {
                $requestParameter = $this->getRequestParameterToCriteria($filter, $requestParameter);
            }
            $requestParameter = array_merge($requestParameter, $defaultCriteria);
            return $requestParameter;
        }

        return $defaultCriteria;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        $grid = $this->metadata->getParameter('grid');
        return (bool) (empty($grid['sortable']))? false : $grid['sortable'];
    }

    /**
     * @param array $sorting
     *
     * @return array
     */
    public function getSorting(array $sorting = [])
    {
        $defaultSorting = array_merge($this->parameters->get('sorting', []), $sorting);

        if ($this->isSortable()) {
            $sorting = $this->getRequestParameter('sorting');
            foreach ($defaultSorting as $key => $value) {
                if (!isset($sorting[$key])) {
                    $sorting[$key] = $value;
                }
            }

            return $sorting;
        }

        return $defaultSorting;
    }

    /**
     * @param string $parameter
     * @param array $defaults
     *
     * @return array
     */
    public function getRequestParameterToCriteria($parameter, $defaults = [])
    {
        $newParam = array($parameter => $this->request->get($parameter, ''));

        if(isset($newParam[$parameter]) && (!empty($newParam[$parameter]) || $newParam[$parameter] === false || $newParam[$parameter] === '0'))
        {
            return array_replace_recursive(
                $defaults,
                $newParam
            );
        }else{
            return $defaults;
        }
    }

    /**
     * @param string $parameter
     * @param array $defaults
     *
     * @return array
     */
    public function getRequestParameter($parameter, $defaults = [])
    {
        return array_replace_recursive(
            $defaults,
            $this->request->get($parameter, [])
        );
    }

    /**
     * @return string|null
     */
    public function getRepositoryMethod()
    {
        if (!$this->parameters->has('repository')) {
            return null;
        }

        $repository = $this->parameters->get('repository');

        return is_array($repository) ? $repository['method'] : $repository;
    }

    /**
     * @return array
     */
    public function getRepositoryArguments()
    {
        if (!$this->parameters->has('repository')) {
            return [];
        }

        $repository = $this->parameters->get('repository');

        if (!isset($repository['arguments'])) {
            return [];
        }

        return is_array($repository['arguments']) ? $repository['arguments'] : [$repository['arguments']];
    }

    /**
     * @return string|null
     */
    public function getFactoryMethod()
    {
        if (!$this->parameters->has('factory')) {
            return null;
        }

        $factory = $this->parameters->get('factory');

        return is_array($factory) ? $factory['method'] : $factory;
    }

    /**
     * @return array
     */
    public function getFactoryArguments()
    {
        if (!$this->parameters->has('factory')) {
            return [];
        }

        $factory = $this->parameters->get('factory');

        if (!isset($factory['arguments'])) {
            return [];
        }

        return is_array($factory['arguments']) ? $factory['arguments'] : [$factory['arguments']];
    }

    /**
     * @param null $message
     *
     * @return mixed|null
     */
    public function getFlashMessage($message)
    {
        return $this->parameters->get('flash', sprintf('%s.%s.%s', $this->metadata->getApplicationName(), $this->metadata->getName(), $message));
    }

    /**
     * @return mixed|null
     */
    public function getSortablePosition()
    {
        return $this->parameters->get('sortable_position', 'position');
    }

    /**
     * @return mixed|null
     */
    public function getSerializationGroups()
    {
        return $this->parameters->get('serialization_groups', []);
    }

    /**
     * @return mixed|null
     */
    public function getSerializationVersion()
    {
        return $this->parameters->get('serialization_version');
    }

    /**
     * @return string|null
     */
    public function getEvent()
    {
        return $this->parameters->get('event');
    }

    /**
     * @return bool
     */
    public function hasPermission()
    {
        return false !== $this->metadata->getParameter('permission');
    }

    /**
     * @return string
     *
     * @throws \LogicException
     */
    public function getPermission()
    {
        $permission = $this->metadata->getParameter('permission');

        if (null === $permission) {
            throw new \LogicException('Current action does not require any authorization.');
        }

        return $permission;
    }

    /**
     * @return bool
     */
    public function isHeaderRedirection()
    {
        $redirect = $this->parameters->get('redirect');

        if (!is_array($redirect) || !isset($redirect['header'])) {
            return false;
        }

        if ('xhr' === $redirect['header']) {
            return $this->getRequest()->isXmlHttpRequest();
        }

        return (bool) $redirect['header'];
    }

    public function getVars()
    {
        return $this->parameters->get('vars', []);
    }

    /**
     * @param array  $parameters
     * @param object $resource
     *
     * @return array
     */
    private function parseResourceValues(array $parameters, $resource): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (empty($parameters)) {
            return ['id' => $accessor->getValue($resource, 'id')];
        }

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $parameters[$key] = $this->parseResourceValues($value, $resource);
            }

            if (is_string($value) && 0 === strpos($value, 'resource.')) {
                $parameters[$key] = $accessor->getValue($resource, substr($value, 9));
            }
        }

        return $parameters;
    }

    /**
     * @return bool
     */
    public function hasGrid()
    {
        return $this->parameters->has('grid');
    }

    /**
     * @return string
     *
     * @throws \LogicException
     */
    public function getGrid()
    {
        if (!$this->hasGrid()) {
            throw new \LogicException('Current action does not use grid.');
        }

        return $this->parameters->get('grid');
    }

    /**
     * @return bool
     */
    public function hasStateMachine()
    {
        return $this->parameters->has('state_machine');
    }

    /**
     * @return string
     */
    public function getStateMachineGraph()
    {
        $options = $this->parameters->get('state_machine');

        return $options['graph'] ?? null;
    }

    /**
     * @return string
     */
    public function getStateMachineTransition()
    {
        $options = $this->parameters->get('state_machine');

        return $options['transition'] ?? null;
    }

    /**
     * @return bool
     */
    public function isCsrfProtectionEnabled()
    {
        return $this->parameters->get('csrf_protection', true);
    }

    /**
     * @param mixed $redirect
     *
     * @return bool
     */
    private function areParametersIntentionallyEmptyArray($redirect): bool
    {
        return isset($redirect['parameters']) && is_array($redirect['parameters']) && empty($redirect['parameters']);
    }
}
