<?php

namespace CTIC\App\Base\Infrastructure\Form;

use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Base\Domain\EntityInterface;
use Symfony\Component\Form\FormInterface;

interface ResourceFormFactoryInterface
{
    /**
     * @param RequestConfiguration $requestConfiguration
     * @param EntityInterface $resource
     * @param string $formType
     *
     * @return FormInterface
     */
    public function create(RequestConfiguration $requestConfiguration, EntityInterface $resource, $formType = null): FormInterface;
}
