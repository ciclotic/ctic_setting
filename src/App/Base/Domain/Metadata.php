<?php

namespace CTIC\App\Base\Domain;

use Doctrine\Common\Inflector\Inflector;

final class Metadata implements MetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $templatesNamespace;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param string $name
     * @param string $applicationName
     * @param array $parameters
     */
    private function __construct(string $name, string $applicationName, array $parameters)
    {
        $this->name = $name;
        $this->applicationName = $applicationName;

        $this->driver = $parameters['driver'];
        $this->templatesNamespace = array_key_exists('templates', $parameters) ? $parameters['templates'] : null;

        $this->parameters = $parameters;
    }

    /**
     * @param string $alias
     * @param array $parameters
     *
     * @return self
     */
    public static function fromAliasAndConfiguration(string $alias, array $parameters): self
    {
        [$applicationName, $name] = self::parseAlias($alias);

        return new self($name, $applicationName, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return $this->applicationName . '.' . $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getHumanizedName(): string
    {
        return strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $this->name)));
    }

    /**
     * {@inheritdoc}
     */
    public function getPluralName(): string
    {
        return Inflector::pluralize($this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplatesNamespace(): ?string
    {
        return $this->templatesNamespace;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter(string $name)
    {
        if (!$this->hasParameter($name)) {
            throw new \InvalidArgumentException(sprintf('Parameter "%s" is not configured for resource "%s".', $name, $this->getAlias()));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters(array $newArray): bool
    {
        $this->parameters = $newArray;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(string $name): string
    {
        if (!$this->hasClass($name)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not configured for resource "%s".', $name, $this->getAlias()));
        }

        return $this->parameters['classes'][$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass(string $name): bool
    {
        return isset($this->parameters['classes'][$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceId(string $serviceName): string
    {
        return sprintf('%s.%s.%s', $this->applicationName, $serviceName, $this->name);
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionCode(string $permissionName): string
    {
        return sprintf('%s.%s.%s', $this->applicationName, $this->name, $permissionName);
    }

    /**
     * @param string $alias
     *
     * @return array
     */
    private static function parseAlias(string $alias): array
    {
        if (false === strpos($alias, '.')) {
            throw new \InvalidArgumentException(sprintf('Invalid alias "%s" supplied, it should conform to the following format "<applicationName>.<name>".', $alias));
        }

        return explode('.', $alias);
    }
}
