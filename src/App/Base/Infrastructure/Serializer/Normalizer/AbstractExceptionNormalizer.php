<?php

namespace CTIC\App\Base\Infrastructure\Serializer\Normalizer;

use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @internal do not use this class in your code
 */
class AbstractExceptionNormalizer
{
    /**
     * @var ExceptionValueMap
     */
    private $messagesMap;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param array $messagesMap
     * @param bool  $debug
     */
    public function __construct(ExceptionValueMap $messagesMap, $debug)
    {
        $this->messagesMap = $messagesMap;
        $this->debug = $debug;
    }

    /**
     * Extracts the exception message.
     *
     * @param \Exception $exception
     * @param int|null   $statusCode
     *
     * @return string
     */
    protected function getExceptionMessage(\Exception $exception, $statusCode = null)
    {
        $showMessage = $this->messagesMap->resolveException($exception);

        if ($showMessage || $this->debug) {
            return $exception->getMessage();
        }

        return array_key_exists($statusCode, Response::$statusTexts) ? Response::$statusTexts[$statusCode] : 'error';
    }
}
