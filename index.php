<?php

require __DIR__ . '/vendor/autoload.php';

$router = LightRoute\LightRouter::getInstance();




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
}, 'post_id')->format(['id' => "[0-8]"]);

$router->addRoute('get', '/go', function() use($router){
    echo "Redirecting...";
    $router->redirect('POST_ID', ['slug' => 'la ferme', 'id' => 4]);
})->format(['id' => "[0-8]"]);

$router->addRoute('post', '/post/add', function(){
    echo "Post variable there:";
    var_dump( $_POST );
});

$router->resolve();

?>

