# Light-Route - A simple php router easy to setup and use
This library provides a simple router configuration and its routes.

# Install

```
composer require flatpixels/light-route
```

# Usage
First of all, you have to setup your routes by doing so:

- Get an instance of the Router:

```php
$router = LightRoute\LightRouter::getInstance();
```

- Register your routes into the router:

```php
$router->addRoute('get', '/posts/', function(){
    echo "Welcome to the posts page";
});
```
Now that you have configured your routes, you have to resolve the current user request URI by calling the resolver:

```php
$router->resolve();
```

# Register routes into the router

Use the method `addRoute(string $requestMethod, $string requestUrl, callable $callable, [, string $routeName  ])` to add routes into the router. The method takes 4 arguments:

- `$requestMethod` : Set the user request method. only support: GET, POST.
- `$requestUrl` : Set the request url that will resolve the route.
- `$callable` : A callback that define the action to execute when the route is reached.
- `$routeName` : (Optional) set the name of the route.

# Register routes with params

You can register routes by passing datas into its url by using the notation `:paramName`. Example:

```php
$route->addRoute('get', 'post/:id', function($id) {
    echo "Show the post: " . $id;
});
```

In the code below, i setup a route by passing the `id` into its url.

You can pass as much as data you want:

```php
$route->addRoute('get', 'post/:id/:slug/:author', function($id, $slug, $author) {
    echo "Show the post: " . $id;
    echo "With the slug: " . $slug;
    echo "Written by: " . $author;
});
```

# Routes with params validator

By passing parameters into the route url, you can use the `format(array $paramsValidators)` method to set a validation constraint:

```php
$route->addRoute('get', 'post/:id', function($id) {
    echo "Show the post: " . $id;
})->format(['id' => "[0-9]"]);
```

The `format(...)` method takes a pair `$key => $value` array where `$key` is a parmater name, and `$value` a regex that represents the valid values for this parameter.

# Redirect to a specific route

You can use the router by calling the `redirect(..)` method, to redirect you to another route. The method takes 2 arguments:

- `$routeName` : The name of the route to which we must be redirected.
- `$params` : (Optional) An array of `$key => $value` where `$key` is a parmater name, and `$value` the value of the paramter:

```php
$router->addRoute('get', '/go/to/single', function() use($router){

    //Redirect to the "single_post" route, with param: id=4
    $router->redirect('single_post', ['id' => 4]);
});
```