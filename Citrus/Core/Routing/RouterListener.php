<?php

namespace Citrus\Core\Routing;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouterListener implements EventSubscriberInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest'
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('_controller')) {
            return;
        }

        if ($this->container['router']->hasMatchedRoute()) {
            $route = $this->container['router']->getMatchedRoute();

            $parameters = array_merge(
                Array('_controller' => $route->getTarget()),
                $route->getParams()
            );
            $request->attributes->add($parameters);

            $request->attributes->set('_route_params', $route->getParams());
        } else {
            throw new NotFoundHttpException(sprintf("No route found for %s", $request->getPathInfo()));
        }

    }
}
