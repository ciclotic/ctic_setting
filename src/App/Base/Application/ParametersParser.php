<?php

namespace CTIC\App\Base\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class ParametersParser implements ParametersParserInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ExpressionLanguage
     */
    private $expression;

    /**
     * @param ContainerInterface $container
     * @param ExpressionLanguage $expression
     */
    public function __construct(ContainerInterface $container, ExpressionLanguage $expression)
    {
        $this->container = $container;
        $this->expression = $expression;
    }

    /**
     * {@inheritdoc}
     */
    public function parseRequestValues(array $parameters, Request $request): array
    {
        return array_map(function ($parameter) use ($request) {
            if (is_array($parameter)) {
                return $this->parseRequestValues($parameter, $request);
            }

            return $this->parseRequestValue($parameter, $request);
        }, $parameters);
    }

    /**
     * @param mixed $parameter
     * @param Request $request
     *
     * @return mixed
     */
    private function parseRequestValue($parameter, Request $request)
    {
        if (!is_string($parameter)) {
            return $parameter;
        }

        if (0 === strpos($parameter, '$')) {
            return $request->get(substr($parameter, 1));
        }

        if (0 === strpos($parameter, 'expr:')) {
            return $this->parseRequestValueExpression(substr($parameter, 5), $request);
        }

        if (0 === strpos($parameter, '!!')) {
            return $this->parseRequestValueTypecast($parameter, $request);
        }

        return $parameter;
    }

    /**
     * @param string $expression
     * @param Request $request
     *
     * @return mixed
     */
    private function parseRequestValueExpression(string $expression, Request $request)
    {
        $expression = preg_replace_callback('/(\$\w+)/', function ($matches) use ($request) {
            $variable = $request->get(substr($matches[1], 1));

            if (is_array($variable) || is_object($variable)) {
                throw new \InvalidArgumentException(sprintf(
                    'Cannot use %s ($%s) as parameter in expression.',
                    gettype($variable),
                    $matches[1]
                ));
            }

            return is_string($variable) ? sprintf('"%s"', $variable) : $variable;
        }, $expression);

        return $this->expression->evaluate($expression, ['container' => $this->container]);
    }

    /**
     * @param mixed $parameter
     * @param Request $request
     *
     * @return mixed
     */
    private function parseRequestValueTypecast($parameter, Request $request)
    {
        [$typecast, $castedValue] = explode(' ', $parameter, 2);

        $castFunctionName = substr($typecast, 2) . 'val';

        Assert::oneOf($castFunctionName, ['intval', 'floatval', 'boolval'], 'Variable can be casted only to int, float or bool.');

        return $castFunctionName($this->parseRequestValue($castedValue, $request));
    }
}
