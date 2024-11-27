<?php

namespace App\Core;

use App\Core\Interfaces\RouteResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private RouteResolverInterface $handlerResolver;

    public function __construct(RouteResolverInterface $handlerResolver)
    {
        $this->handlerResolver = $handlerResolver;
    }

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function options(string $path, $handler): void
    {
        $this->addRoute('OPTIONS', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = rtrim($request->getPathInfo(), '/') ?: '/';

        if (!isset($this->routes[$method][$path])) {
            return $this->applyMiddleware(new Response('nnnNot Found', 404), $request);
        }

        $handler = $this->routes[$method][$path];
        $response = $this->handlerResolver->resolve($handler, $request);

        return $this->applyMiddleware($response, $request);
    }

    private function applyMiddleware(Response $response, Request $request): Response
    {
        foreach ($this->middleware as $middleware) {
            $response = $middleware($request, $response);
        }

        return $response;
    }
}
