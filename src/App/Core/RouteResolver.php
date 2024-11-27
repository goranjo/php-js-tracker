<?php

namespace App\Core;

use App\Core\Interfaces\RouteResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteResolver implements RouteResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(mixed $handler, Request $request): Response
    {
        // [TrackerController::class, 'track']
        if (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;

            if (!$this->container->has($controller)) {
                return new Response("Controller $controller not found.", 404);
            }

            $controllerInstance = $this->container->get($controller);

            if (!method_exists($controllerInstance, $method)) {
                return new Response("Method $method not found in controller $controller.", 404);
            }

            return $controllerInstance->$method($request);
        }

        return new Response('Not Found', 404);
    }
}
