<?php

namespace Citrus\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;

use Citrus\Core\Routing\RoutingServiceProvider;

use Citrus\Core\Event\EventServiceProvider;

use Citrus\Core\KernelServiceProvider;
use Citrus\Core\View\TemplateEngineServiceProvider;

use Citrus\Core\System\ServiceContainerInterface;
use Citrus\Core\System\ServiceProviderInterface;

use Citrus\Core\Controller\ControllerResolverServiceProvider;
//use Citrus\Core\Debug\DebugServiceProvider;

class App extends \Pimple implements TerminableInterface, ServiceContainerInterface
{
    const VERSION = '2.0a-DEV';

    protected $paths     = Array();

    protected $container = Array();

    protected $providers = Array();

    protected $context;

    public function __construct(Request $request, $debug = false)
    {
        $this['request'] = $request;
        $this['debug']   = $debug;
    }

    /*public function __get($name) {
        return $this[$name];
    }*/

    public function run()
    {
        $this->boot();
        $response = $this['kernel']->handle($this['request'])->send();
        return $response;

    }

    public function boot()
    {
        $this->registerCoreProviders();

        // exception handling
        $exception_listener = new ExceptionListener('Citrus\Core\Controller\ErrorController::doException');
        $this['event_dispatcher']->addSubscriber($exception_listener);

        // booting service providers
        foreach ($this->providers as $provider) {
            $provider->boot($this);
        }

        return $this;
    }

    public function registerProvider(ServiceProviderInterface $provider)
    {
        $this->providers[] = $provider;
        $provider->register($this);
    }

    public function set($id, $value, $force = false) {
        if (!array_key_exists($id, $this->providers) || $force) {

        }
    }

    public function registerProviders()
    {
        // $this->registerProvider(new DatabaseServiceProvider, "logger");
    }

    public function registerCoreProviders()
    {
        // $this->registerProvider(new LoggerServiceProvider, "logger");
        // $this->registerProvider(new HttpCacheServiceProvider($this));
        // $this->registerProvider(new DebugServiceProvider());

        $this->registerProvider(new EventServiceProvider());
        $this->registerProvider(new RoutingServiceProvider());
        $this->registerProvider(new ControllerResolverServiceProvider());
        $this->registerProvider(new KernelServiceProvider());
    }

    public function terminate(Request $request, Response $response)
    {
        # code...
    }

    public function get($id)
    {
        if ( !isset( $this[$id] ) ) {
            throw new \InvalidArgumentException(sprintf('Service provider "%s" does not exist.', $id));
        }
        return $this[$id];
    }

}
