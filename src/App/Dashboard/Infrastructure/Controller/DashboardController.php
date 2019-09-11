<?php
namespace CTIC\App\Dashboard\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityActions;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\Base\Infrastructure\View\Rest\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $action = 'dashboard';

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $this->eventDispatcher->dispatchMultiple($action, $configuration, $resources);

        $view = View::create();

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate($action. '.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                ])
            ;
        }

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }
}