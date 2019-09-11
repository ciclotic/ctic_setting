<?php

namespace CTIC\App\Base\Infrastructure\Repository\Exception;

class ExistingResourceException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Given resource already exists in the repository.');
    }
}
