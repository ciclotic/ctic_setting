<?php

namespace CTIC\App\Base\Infrastructure\Templating\Helper;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use CTIC\App\Base\Infrastructure\View\GridView;
use Symfony\Component\Templating\Helper\Helper;

/**
 * @final
 */
class BulkActionGridHelper extends Helper
{
    /**
     * @var BulkActionGridRendererInterface
     */
    private $bulkActionGridRenderer;

    /**
     * @param BulkActionGridRendererInterface $bulkActionGridRenderer
     */
    public function __construct(BulkActionGridRendererInterface $bulkActionGridRenderer)
    {
        $this->bulkActionGridRenderer = $bulkActionGridRenderer;
    }

    /**
     * @param GridView $gridView
     * @param Action $bulkAction
     * @param mixed|null $data
     *
     * @return string
     */
    public function renderBulkAction(GridView $gridView, Action $bulkAction, $data = null): string
    {
        return $this->bulkActionGridRenderer->renderBulkAction($gridView, $bulkAction, $data);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'sylius_bulk_action_grid';
    }
}
