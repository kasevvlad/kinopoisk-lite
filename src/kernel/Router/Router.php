<?php

namespace App\Kernel\Router;

use App\Kernel\Controller\Controller;
use App\Kernel\View\View;

class Router
{

    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct(
        private View $view
    )
    {
        $this->initRoutes();
    }

    public function dispatch(string $uri, string $method): void
    {
        $route = $this->findRoute($uri, $method);

        if (!$route){
            $this->notFound();
        }

        if (is_array($route->getAction())){
            [$controller, $action] = $route->getAction();

            $controller = new $controller();
            /** @var Controller $controller */
            $controller = new $controller();
            call_user_func([$controller, 'setView'], $this->view);
            call_user_func([$controller, $action]);
        }else{
            call_user_func($route->getAction());
        }

    }

    private function notFound():void
    {
        echo '404 | Not found';
        exit;
    }

    private function findRoute(string $uri, string $method): Route|false
    {
        if (!isset($this->routes[$method][$uri])){
            return false;
        }

        return $this->routes[$method][$uri];
    }

    private function initRoutes()
    {
        $routes = $this->getRoutes();
        foreach ($routes as $route){
            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }
    /**
     * @return Route[]
     * */
    private function getRoutes(): array
    {
        return require_once APP_PATH.'/config/routes.php';
    }
}
