<?php

namespace CTIC\App\Base\Infrastructure\Twig;

use CTIC\App\Base\Infrastructure\Templating\Helper\GridHelper;

final class GridExtension extends \Twig_Extension
{
    /**
     * @var GridHelper
     */
    private $gridHelper;

    /**
     * @param GridHelper $gridHelper
     */
    public function __construct(GridHelper $gridHelper)
    {
        $this->gridHelper = $gridHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('ctic_grid_render', [$this->gridHelper, 'renderGrid'], ['is_safe' => ['html']]),
            new \Twig_Function('ctic_grid_render_field', [$this->gridHelper, 'renderField'], ['is_safe' => ['html']]),
            new \Twig_Function('ctic_grid_render_action', [$this->gridHelper, 'renderAction'], ['is_safe' => ['html']]),
            new \Twig_Function('ctic_grid_render_filter', [$this->gridHelper, 'renderFilter'], ['is_safe' => ['html']]),
        ];
    }
}
