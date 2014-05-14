<?php
namespace Citrus\Core;
use Citrus\Core\System\ServiceProviderInterface;
use Symfony\Component\HttpKernel\HttpKernel;

class KernelServiceProvider implements ServiceProviderInterface
{
    public function register($app)
    {
        $app['kernel'] = function($app) {
            return new HttpKernel($app['event_dispatcher'], $app['controller_resolver']);
        };
    }

    public function boot($app)
    {}
}
