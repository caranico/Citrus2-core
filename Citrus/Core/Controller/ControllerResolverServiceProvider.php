<?php
namespace Citrus\Core\Controller;
use Citrus\Core\System\ServiceProviderInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class ControllerResolverServiceProvider implements ServiceProviderInterface
{
    public function register($app)
    {
        $app['controller_resolver'] = function ($app) {
            return new ControllerResolver(null, $app);
        };
    }

    public function boot($app)
    {}
}
