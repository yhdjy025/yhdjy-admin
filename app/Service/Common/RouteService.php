<?php

namespace App\Service\Common;

use Illuminate\Support\Facades\Route;

class RouteService
{
    public static function getRoutes()
    {
        $allRoutes = Route::getRoutes()->getRoutes();
        $namedRoutes = Route::getRoutes()->getRoutesByName();
        $namedRoutesUri = [];
        foreach ($namedRoutes as $routeName => $route) {
            $namedRoutesUri[$route->uri] = $routeName;
        }
        $routes = [];
        $middlewareClosure = function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        };

        foreach ($allRoutes as $route) {

            if (is_callable([$route, 'controllerMiddleware'])) {
                $middleware = implode(', ', array_map($middlewareClosure, array_merge($route->middleware(), $route->controllerMiddleware())));
            } else {
                $middleware = implode(', ', $route->middleware());
            }
            $routes[$route->uri] = [
                'name'       => isset($namedRoutesUri[$route->uri]) ? $namedRoutesUri[$route->uri] : '',
                'url'        => $route->uri,
                'methods'    => implode(", ", $route->methods()),
                'action'     => $route->getActionName(),
                'middleware' => $middleware,
                'count'      => 0
            ];
        }

        return $routes;
    }
}
