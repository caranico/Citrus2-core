<?php

namespace Citrus\Core\Routing;
use Citrus\Core\System\ServiceProviderInterface;
// use Symfony\Component\Routing\RouteCollection;
// use Symfony\Component\Routing\Route;
// use Symfony\Component\Routing\Matcher\UrlMatcher;
// use Symfony\Component\Routing\RequestContext;
// use Symfony\Component\HttpKernel\EventListener\RouterListener;

class RoutingServiceProvider implements ServiceProviderInterface
{
    protected $app;

    public function register($app)
    {
        $app['router'] = function($app) {
            return new Router($app['request']);
        };
    }

    public function boot($app)
    {
        $app['event_dispatcher']->addSubscriber(new RouterListener($app));

        $routes = $app['routes'];
        $request = $app['request'];
        foreach ($app['routes'] as $k => $r) {
            if (array_key_exists("url", $r) && array_key_exists("target", $r)) {
                $conditions = array_key_exists("conditions", $r) ? $r['conditions'] : Array();
                $route = new Route($r["url"], $request->getPathInfo(), $r['target'], $conditions);
                if ($route->isMatched()) {
                    $app['router']->setRoute($route);
                    break;
                }
            }
        }
    }

}
