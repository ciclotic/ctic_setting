<?php

namespace CTIC\App\Base\Infrastructure\Form;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class ResourceFormFactory implements ResourceFormFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(RequestConfiguration $requestConfiguration, EntityInterface $resource, $formType = null): FormInterface
    {
        if ($formType == null) {
            $formType = $requestConfiguration->getFormType();
        }
        $formOptions = $requestConfiguration->getFormOptions();

        if ($requestConfiguration->isHtmlRequest()) {
            return $this->formFactory->create($formType, $resource, $formOptions);
        }

        return $this->formFactory->createNamed('', $formType, $resource, array_merge($formOptions, ['csrf_protection' => false]));
    }
}
