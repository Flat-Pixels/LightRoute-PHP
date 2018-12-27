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