<?php

namespace LightRoute;

use LightRoute\Exception\LightRouteException;

class LightRouter
{

    /**
     * Keep the single instace of this class
     *
     * @var LightRouter
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
     * @return LightRouter
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Register routes into the router
     *
     * @param string $requestMethod
     * @param string $routeUrl
     * @param callable $callback
     * @param string $name Name of the route
     * @return LightRoute | \Exception
     */
    public function addRoute(string $requestMethod, string $routeUrl, callable $callback, ?string $name = null)
    {
        $requestMethod = strtoupper($requestMethod);
        if (in_array($requestMethod, $this->supportedRequestMethods, true)) {
            $route = new LightRoute($routeUrl, $callback, $name);
            if (!$this->hasRegistered($route)) {
                $this->routes[$requestMethod][] = $route;
                return $route;
            }
            throw new LightRouteException("Route [" . $requestMethod . "] is a doublon");
        }
        throw new LightRouteException("Method " . $requestMethod . " is not supported");
    }

    /**
     * Redirect to a route by using GET as request method
     *
     * @param string $routeName Name of the route to be redirected to
     * @param array $queryParams Query params that needs the route
     * @return void | \Exception
     */
    public function redirect(string $routeName, array $queryParams = [])
    {
        if (isset($this->routes['GET'])) {
            foreach ($this->routes['GET'] as $route) {
                if (strcasecmp($route->getName(), $routeName) === 0) {
                    $path = preg_replace_callback('#:[\w]+#', function ($matches) use ($queryParams) {
                        $paramName = str_replace(':', '', $matches[0]);
                        if (!isset($queryParams[$paramName])) {
                            throw new LightRouteException("Redirect method need " . strtoupper($paramName) . " to work correctly.");
                        }
                        return $queryParams[$paramName];
                    }, $route->getUrl());
                    header('Location:' . $path);
                    exit;
                    //return true;
                }
            }
        }
        throw new LightRouteException("Redirect route for : " . $routeName . " not found");
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

        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route) {
                if ($route->matchesToUrl($requestUrl)) {
                    if ($route->isQueryParamsValid()) {
                        return $route->execute();
                    } else {
                        throw new LightRouteException("Route params not valid");
                    }
                }
            }
        }
        throw new LightRouteException("Route for url: " . $requestUrl . " not found");
    }

    /**
     * Check if a route has been already registered
     *
     * @param LightRoute $checkRoute
     * @return boolean | Route
     */
    private function hasRegistered(LightRoute $checkRoute)
    {
        foreach ($this->routes as $requestMethod => $routes) {
            foreach ($routes as $route) {
                if ($route->getUrl() === $checkRoute->getUrl()||
                    (!is_null($route->getName()) && $route->getName() === $checkRoute->getName())) {
                    return $route;
                }
            }
        }
        return false;
    }
}
