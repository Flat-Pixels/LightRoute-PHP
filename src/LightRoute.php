<?php

namespace LightRoute;

class LightRoute
{
    /**
     * Route url
     *
     * @var string
     */
    private $url;
    
    /**
     * Route params value
     *
     * @var array
     */
    private $queryParams = [];

    /**
     * Route action when it is reached
     *
     * @var callbable
     */
    private $callback;

    /**
     * Regex that validates each route url params
     *
     * @var array
     */
    private $queryParamsFormat = [];

    /**
     * Name of the route
     *
     * @var string
     */
    private $name;


    /**
     * Create a route
     *
     * @param string $url url of the route
     * @param callable $callback action to call when a route is executed
     */
    public function __construct(string $url, callable $callback, ?string $name = null)
    {
        $this->setUrl($url);
        $this->callback = $callback;
        $this->name = $name;
    }

    /**
     * Check if a url matches to the current route
     *
     * @param string $url the request url
     * @return boolean
     */
    public function matchesToUrl(string $url): bool
    {
        preg_match_all('#:([\w]+)#', $this->url, $paramsName);
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->url);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $paramsValue)) {
            return false;
        }
        array_shift($paramsValue);
        foreach($paramsName[1] as $key => $name) {
            $this->queryParams[$name] = $paramsValue[$key];
        }
        return true;
    }

    /**
     * Check if all query params are valid
     *
     * @return boolean
     */
    public function isQueryParamsValid(): bool
    {
        if (!empty($this->queryParams)){
            foreach ($this->queryParamsFormat as $key => $regex) {
                if (!preg_match_all('/' . $regex .'/', $this->queryParams[$key])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Add validator for query params
     *
     * @param array $queryParamsFormat array of query params validator
     * @return LightRoute
     */
    public function format($queryParamsFormat): LightRoute
    {
        $this->queryParamsFormat = $queryParamsFormat;
        return $this;
    }

    /**
     * Set the route name
     *
     * @param string $name Name of the route
     * @return LightRoute
     */
    public function name(string $name): LightRoute
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the name of a route
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set a route url
     *
     * @param string $url
     * @return void
     */
    public function setUrl($url): void
    {
        $this->url = $url === '/' ? '/' : rtrim($url, '/');
    }
    
    /**
     * Get the url of a route
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Execute a route
     *
     * @return void
     */
    public function execute()
    {
        call_user_func_array($this->callback, $this->queryParams);
    }

}