<?php

namespace CTIC\App\Base\Infrastructure\Twig;

use CTIC\App\Base\Infrastructure\Templating\Helper\BulkActionGridHelper;

final class BulkActionGridExtension extends \Twig_Extension
{
    /**
     * @var BulkActionGridHelper
     */
    private $bulkActionGridHelper;

    /**
     * @param BulkActionGridHelper $bulkActionGridHelper
     */
    public function __construct(BulkActionGridHelper $bulkActionGridHelper)
    {
        $this->bulkActionGridHelper = $bulkActionGridHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function(
                'ctic_grid_render_bulk_action',
                [$this->bulkActionGridHelper, 'renderBulkAction'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
