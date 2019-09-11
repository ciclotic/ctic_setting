<?php

namespace CTIC\App\Base\Infrastructure\Parser;

use Symfony\Component\HttpFoundation\Request;

interface OptionsParserInterface
{
    /**
     * @param array $parameters
     * @param Request $request
     * @param mixed $data
     *
     * @return array
     */
    public function parseOptions(array $parameters, Request $request, $data = null): array;
}
