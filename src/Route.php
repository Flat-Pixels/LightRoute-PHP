<?php

namespace LightRoute;


class Route
{
    /**
     * Define the url of the route
     *
     * @var string
     */
    private $url;
    
    /**
     * Keep params of a route
     *
     * @var array
     */
    private $vars = [];

    /**
     * Define the action that will be excute when the route is reached
     *
     * @var Callbable
     */
    private $callback;

    public function __construct(string $url, Callable $callback)
    {
        $this->setUrl($url);
        $this->callback = $callback;
    }

    public function matches($url)
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->url);
        $regex = "#^$path$#i";

        
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->vars = $matches;

        return true;
    }

    /**
     * Set a route url
     *
     * @param string $url
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url === '/' ? '/' : rtrim($url, '/');
    }

    /**
     * Execute a route
     *
     * @return void
     */
    public function execute()
    {
        call_user_func_array($this->callback, $this->vars);
    }

}