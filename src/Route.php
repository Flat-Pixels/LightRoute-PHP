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
     * Define the action that will be excute when the route is reached
     *
     * @var Callbable
     */
    private $callback;

    public function __construct(string $url, Callable $callback)
    {
        $this->url = $url;
        $this->callback = $callback;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCallback()
    {
        return $this->callback;
    }
}