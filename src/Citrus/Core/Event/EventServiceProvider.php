<?php

namespace Citrus\Core\Event;
use Citrus\Core\System\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventServiceProvider implements ServiceProviderInterface
{
    public function register($app)
    {
        $app['event_dispatcher'] = function ($app) {
            return new EventDispatcher();
        };
    }

    public function boot($app)
    {
        # code...
    }


}
