<?php
require "src/Router.php";

$router = LightRoute\Router::getInstance();

$router->addRoute('post', '/post/add', function(){
    echo "Post variable there:";
    var_dump( $_POST );
});


//On enregistre les differentes routes
$router->addRoute('get', '/', function(){
    echo "Welcome to the home page";
});

$router->addRoute('get', '/posts/', function(){
    echo "Welcome to the posts page";
});

$router->addRoute('get', '/post/:id/:slug', function( $id, $slug ){
    echo "Afficher le post ayant l'id: " . $id;
    echo "Afficher le post ayant le slug: " . $slug;
})->format(['id' => "[0-8]"]);



$router->resolve();

?>

