<?php
require "src/Router.php";

$router = LightRoute\Router::getInstance();

//On enregistre les differentes routes
$router->addRoute('get', '/', function(){
    echo "Welcome to the home page";
});

$router->addRoute('get', '/posts/', function(){
    echo "Welcome to the posts page";
});

//Demain
//Implementer des routes pour resoudre ces url
$router->addRoute('get', '/post/:id', function( $id ){
    echo "Afficher le post ayant l'id: " . $id;
});


$router->resolve();

