<?php
/*
 * This file is part of Citrus.
 *
 * (c) Rémi Cazalet <remi@caramia.fr>
 * Nicolas Mouret <nicolas@caramia.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package Citrus\Core\routing
 * @subpackage Citrus\Core\Routing\Router
 * @author Rémi Cazalet <remi@caramia.fr>
 * @license http://opensource.org/licenses/mit-license.php The MIT License
 */



/**
 * Thanks to http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
 */

namespace Citrus\Core\Routing;

use Symfony\Component\HttpFoundation\Request;

class Router
{

    private $request_uri;

    private $controller;

    private $routes = Array();

    private $route;

    private $params;

    public function __construct(Request $request)
    {
        $this->request_uri = $request->getPathInfo();
        $this->routes = Array();

    }

    public function map($rule, $target = Array(), $conditions = Array())
    {
        $this->routes[$rule] = new Route(
            $rule, $this->request_uri, $target, $conditions
        );
        return $this;
    }

    public function execute()
    {
        if (count($this->routes)) {
            foreach($this->routes as $route) {
                if ($route->isMatched()) {
                    $this->setRoute($route);
                    return;
                }
            }
            throw new NoRouteFoundException();
        }
        return $this;
    }

    public function executeRoutes($routes)
    {
        if (count($routes)) {
            foreach($routes as $r) {
                $route = new Route(
                    $r["url"],
                    $this->request_uri,
                    isset($r["target"]) ? $r["target"] : Array(),
                    isset($r["conditions"]) ? $r['conditions'] : Array()
                );
                if ($route->isMatched()) {
                    $this->setRoute($route);
                    return $route;
                }
            }
            // throw new NoRouteFoundException();
        }
        return false;
    }

    public function getRouteURL()
    {
        return $this->route->url;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        $this->params = $this->route->getParams();
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getMatchedRoute()
    {
        return $this->route;
    }

    public function hasMatchedRoute()
    {
        // var_dump( $this->route instanceof Route);
        return $this->route !== null && $this->route instanceof Route;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
        return false;
    }

    public function removeParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            unset($this->params[$name]);
        }
        return $this;
    }

    public function removeParams($params)
    {
        if (count($params)) foreach ($params as $k) {
            $this->removeParam($k);
        }
        return $this;
    }
}
