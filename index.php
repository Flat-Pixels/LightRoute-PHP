<?php
require "src/Router.php";

$router = LightRoute\Router::getInstance();

//On enregistre les differentes routes
$router->addRoute('get', '/', function(){
    echo "Welcome to the home page";
});

$router->addRoute('get', '/posts', function(){
    echo "Welcome to the posts page";
});

$router->resolve();

