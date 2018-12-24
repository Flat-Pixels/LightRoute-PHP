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

    private $paramsValidators = [];

    public function __construct(string $url, Callable $callback)
    {
        $this->setUrl($url);
        $this->callback = $callback;
    }

    public function matches($url)
    {
        /*
        * Check if the request url pattern
        * Matches to a route url
        */
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->url);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);

        /* Save url params urlname and their values */
        preg_match_all('#:([\w]+)#', $this->url, $paramsName);
        $paramsName = $paramsName[1];
        $params = [];
        foreach($paramsName as $key => $name ){
            $params[$name] = $matches[$key];
        }

        //var_dump($params);

        /* Check if params are valid */

        foreach($this->paramsValidators as $key => $value)
        {
            /*if(($value === 'int' && !is_numeric($params[$key])) || ($value === 'string' && !is_string($params[$key]))){
                return false;
            }*/
            var_dump($value);
            if(!preg_match_all('/' . $value .'/', $params[$key])){
                return false;
            }
        }
        $this->vars = $matches;

        return true;
    }

    public function validateParams($args)
    {
        $this->paramsValidators = $args;
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