<?php
namespace LightRoute;

require "Exception/RouteException.php";
require "Route.php";

use LightRoute\Exception\RouteException;

class Router{

    /**
     * Keep the single instace of this class
     *
     * @var Router
     */
    private static $instance;

    /**
     * Keep the supported request methods of the router 
     *
     * @var array
     */
    private $supportedRequestMethods = ['GET', 'POST'];

    /**
     * Kepp all routes registered in the router
     *
     * @var array
     */
    private $routes = [];

    private function __construct()
    {

    }

    /**
     * Create an instance of Router
     *
     * @return Router
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router;
        }
        return self::$instance;
    }

    /**
     * Register routes to the router
     *
     * @param string $requestMethod
     * @param string $routeUrl
     * @param Callable $callback
     * @return Route
     */
    public function addRoute(string $requestMethod, string $routeUrl, Callable $callback)
    {
        $requestMethod = strtoupper($requestMethod);
        if (in_array($requestMethod, $this->supportedRequestMethods, true)) {
            $route = new Route($this->filterRouteUrl($routeUrl), $callback);
            if (!$this->hasRegistered($requestMethod, $route)) {
                return $this->routes[$requestMethod][] = $route;
            }
            throw new RouteException("Routes doublon not accepted");
        }
        throw new RouteException("Method " . $requestMethod . " is not supported");
    }

    /**
     * Resolve the current user's request
     *
     * @return void
     */
    public function resolve()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUrl = $_SERVER['REQUEST_URI'];
        if ($route = $this->getRouteByUrl($requestMethod, $this->filterRouteUrl($requestUrl))) {
            $route->getCallback()();
            return;
        }
        throw new RouteException("Route not found");
    }

    private function hasRegistered($requestMethod, $checkRoute)
    {
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route) {
                if ($route === $checkRoute) {
                    return $route;
                }
            }
        }
        return false;
    }

    private function getRouteByUrl($requestMethod, $routeUrl)
    {
        if (isset($this->routes[$requestMethod])) {
            foreach($this->routes[$requestMethod] as $route) {
                if($routeUrl === $route->getUrl()) {
                    return $route;
                }
            }
        }
        return false;
    }

    private function filterRouteUrl($url)
    {
        return rtrim($url, '/');
    }


}