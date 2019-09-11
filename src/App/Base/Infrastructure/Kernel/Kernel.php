<?php

namespace CTIC\App\Base\Infrastructure\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;


class Kernel extends BaseKernel
{
    private $baseDir;
    private $buildContainer;
    private $httpKernel;

    public function __construct($basedir = '/', \Closure $buildContainer = null, HttpKernelInterface $httpKernel = null, $environment = 'custom', $debug = false)
    {
        parent::__construct($environment, $debug);

        $this->baseDir = $basedir;
        $this->buildContainer = $buildContainer;
        $this->httpKernel = $httpKernel;
    }

    public function registerBundles()
    {
        return array();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }

    public function getProjectDir()
    {
        return $this->baseDir;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    protected function build(ContainerBuilder $container)
    {
        if ($build = $this->buildContainer) {
            $build($container);
        }
    }

    protected function getHttpKernel()
    {
        return $this->httpKernel;
    }
}