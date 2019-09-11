<?php

namespace CTIC\App\Base\Domain\Exception;

class RaceConditionException extends UpdateHandlingException
{
    /**
     * @param \Exception|null $previous
     */
    public function __construct(?\Exception $previous = null)
    {
        parent::__construct(
            'Operated entity was previously modified.',
            'race_condition_error',
            409,
            null !== $previous ? $previous->getCode() : 0,
            $previous
        );
    }
}
