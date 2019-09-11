<?php

namespace CTIC\App\Base\Infrastructure\Templating;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TwigEngine implements EngineInterface
{
    /**
     * @var \Twig_Loader_Filesystem
     */
    protected $loader;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Loader_Filesystem $loader
     * @param \Twig_Environment $twig
     */
    public function __construct(
        \Twig_Loader_Filesystem $loader,
        \Twig_Environment $twig
    ) {

        $this->loader = $loader;
        $this->twig = $twig;
    }

    /**
     * Renders a template.
     *
     * @param string|TemplateReferenceInterface $name       A template name or a TemplateReferenceInterface instance
     * @param array                             $parameters An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function render($name, array $parameters = array())
    {
        try{
            $template = $this->twig->load($name);
        } catch (\Exception $e) {
            throw new \RuntimeException('Template can not be rendered.');
        }

        return $template->render($parameters);
    }

    /**
     * Returns true if the template exists.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if the template exists, false otherwise
     *
     * @throws \RuntimeException if the engine cannot handle the template name
     */
    public function exists($name)
    {
        return $this->loader->exists($name);
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if this class supports the given template, false otherwise
     */
    public function supports($name)
    {
        return true;
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     *
     * @return Response A Response instance
     *
     * @throws \RuntimeException if the template cannot be rendered
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        if ($response === null)
        {
            $response = new Response();
        }

        $renderedView = $this->render($view, $parameters);

        $response->setContent($renderedView);

        return $response;
    }
}