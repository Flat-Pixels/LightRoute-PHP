<?php
namespace LightRoute;

require "Exception/RouteException.php";


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
        if(in_array($requestMethod, $this->supportedRequestMethods, true))
        {
            if(!isset($this->routes[$requestMethod][$routeUrl])){
                return $this->routes[$requestMethod][$routeUrl] = $callback;
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
        
        if(in_array($requestMethod, $this->supportedRequestMethods))
        {
            if(isset($this->routes[$requestMethod][$requestUrl]))
            {
                return $this->routes[$requestMethod][$requestUrl]();
            }
            throw new RouteException("Route not found");
        }
        throw new RouteException("Method " . $requestMethod . " is not supported");
    }


}