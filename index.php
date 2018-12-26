<?php
require __DIR__ . '/vendor/autoload.php';

/*
* Examples of routes registering
* [1] : Get an instance of the router
* [2] : Register the route into the route
* [3] : Resolve the current user request
*/

//[1]

//Create an instance of the router
$router = LightRoute\LightRouter::getInstance();

//[2]

//Routes to call in GET without any params
$router->addRoute('get', '/', function(){
    echo "Welcome to the home page";
});

$router->addRoute('get', '/posts/', function(){
    echo "Welcome to the posts page";
});

//Route to call in GET with params
$router->addRoute('get', '/post/:id/:slug', function( $id, $slug ){
    echo "Post id: " . $id;
    echo "Post slug: " . $slug;
}, 'single_post')->format(['id' => "[0-8]"]);

//Route to call in POST
$router->addRoute('post', '/post/add', function(){
    var_dump( $_POST );
});

//Route redirects to another route
$router->addRoute('get', '/go', function() use($router){
    $router->redirect('single_post', ['slug' => 'la ferme', 'id' => 4]);
})->format(['id' => "[0-8]"]);

//[3]
$router->resolve();

