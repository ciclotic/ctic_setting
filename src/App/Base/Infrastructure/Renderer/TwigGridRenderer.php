<?php

namespace CTIC\App\Base\Infrastructure\Renderer;

use CTIC\App\Base\Infrastructure\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TwigGridRenderer implements GridRendererInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var ServiceRegistryInterface
     */
    private $fieldsRegistry;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormTypeRegistryInterface
     */
    private $formTypeRegistry;

    /**
     * @var string
     */
    private $defaultTemplate;

    /**
     * @var array
     */
    private $actionTemplates;

    /**
     * @var array
     */
    private $filterTemplates;

    /**
     * @param \Twig_Environment $twig
     * @param ServiceRegistryInterface $fieldsRegistry
     * @param FormFactoryInterface $formFactory
     * @param FormTypeRegistryInterface $formTypeRegistry
     * @param string $defaultTemplate
     * @param array $actionTemplates
     * @param array $filterTemplates
     */
    public function __construct(
        \Twig_Environment $twig,
        ServiceRegistryInterface $fieldsRegistry,
        FormFactoryInterface $formFactory,
        FormTypeRegistryInterface $formTypeRegistry,
        string $defaultTemplate,
        array $actionTemplates = [],
        array $filterTemplates = []
    ) {
        $this->twig = $twig;
        $this->fieldsRegistry = $fieldsRegistry;
        $this->formFactory = $formFactory;
        $this->formTypeRegistry = $formTypeRegistry;
        $this->defaultTemplate = $defaultTemplate;
        $this->actionTemplates = $actionTemplates;
        $this->filterTemplates = $filterTemplates;
    }

    /**
     * @return string
     */
    public function getDefaultTemplate(): string
    {
        return $this->defaultTemplate;
    }

    /**
     * @param string $defaultTemplate
     */
    public function setDefaultTemplate(string $defaultTemplate): void
    {
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function render(GridViewInterface $gridView, ?string $template = null)
    {
        return $this->twig->render($template ?: $this->defaultTemplate, ['grid' => $gridView]);
    }

    /**
     * {@inheritdoc}
     */
    public function renderField(GridViewInterface $gridView, Field $field, $data)
    {
        /** @var FieldTypeInterface $fieldType */
        $fieldType = $this->fieldsRegistry->get($field->getType());
        $resolver = new OptionsResolver();
        $fieldType->configureOptions($resolver);
        $options = $resolver->resolve($field->getOptions());

        return $fieldType->render($field, $data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function renderAction(GridViewInterface $gridView, Action $action, $data = null)
    {
        $type = $action->getType();
        if (!isset($this->actionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for action type "%s".', $type));
        }

        return $this->twig->render($this->actionTemplates[$type], [
            'grid' => $gridView,
            'action' => $action,
            'data' => $data,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function renderFilter(GridViewInterface $gridView, Filter $filter)
    {
        $template = $this->getFilterTemplate($filter);
        $type = $filter->getType();

        $form = $this->formFactory->createNamed(
            null,
            FormType::class,
            [],
            [
                //'allow_extra_fields' => true,
                //'csrf_protection' => false,
                'required' => false,
            ]
            );
        $form->add(
            $filter->getName(),
            $this->formTypeRegistry->get($type, ucfirst($type) . 'Type'),
            $filter->getFormOptions()
        );

        $criteria = $gridView->getParameters()->get('criteria', []);
        $form->submit($criteria);

        return $this->twig->render($template, [
            'grid' => $gridView,
            'filter' => $filter,
            'form' => $form->get($filter->getName())->createView(),
        ]);
    }

    /**
     * @param Filter $filter
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function getFilterTemplate(Filter $filter): string
    {
        $template = $filter->getTemplate();
        if (null !== $template) {
            return $template;
        }

        $type = $filter->getType();
        if (!isset($this->filterTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for filter type "%s".', $type));
        }

        return $this->filterTemplates[$type];
    }
}
