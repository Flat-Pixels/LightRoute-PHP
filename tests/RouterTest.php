<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use LightRoute\LightRouter;
use LightRoute\LightRoute;
use LightRoute\Exception\LightRouteException;

final class RouterTest extends TestCase
{
    private $router;

    protected function setup()
    {
        $this->router = LightRouter::getInstance();
    }

    /**
     * Check if route initialize once
     *
     * @return void
     */
    public function testRouteInstanceOnce(): void
    {
        $this->assertSame(LightRouter::getInstance(), LightRouter::getInstance());
    }

    /**
     * Check if a route with the same request method(Get or Post)
     * Can registered only once into theRouter
     *
     * @return void
     */
    public function testDoublonRouteRegisterIntoRouter(): void
    {
        $this->expectException(LightRouteException::class);
        $this->router->addRoute('get', '/', function () {
        });
        $this->router->addRoute('get', '/', function () {
        });
    }

    /**
     * Can not register route with unsupported Request Method
     *
     * @return void
     */
    public function testRegisterRouteWithUnsupportedRequestMethod(): void
    {
        $this->expectExceptionMessage("Method PUT is not supported");
        $this->router->addRoute('put', '/:id', function () {
        });
    }

    /**
     * Check redirection with a wrong route name
     *
     * @return void
     */
    public function testRouteCantRedirectionWithoutName(): void
    {
        $this->expectException(LightRouteException::class);
        $this->router->addRoute('get', '/post', function () {
        }, "GETPOST");
        $this->router->redirect("getpo");
    }

    /*
     * Check redirection with route with name in different case
     *
     * @return void
     *
    public function testRouteRedirectionWithInsensibleCaseName(): void
    {
        $this->assertEquals(true, $this->router->redirect("getpost"));
    }*/

    /**
     * Check route registration with route name doublon
     *
     * @return void
     */
    public function testRouteNameDoublon(): void
    {
        $this->expectException(LightRouteException::class);
        $this->router->addRoute('get', '/blog', function () {
        }, "blog");
        $this->router->addRoute('post', '/single', function () {
        }, "blog");
    }
}
