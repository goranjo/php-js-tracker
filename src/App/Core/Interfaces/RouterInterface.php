<?php

namespace App\Core\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouterInterface
{

    public function get(string $path, callable|array $handler): void;
    public function post(string $path, callable|array $handler): void;
    public function addRoute(string $method, string $path, callable|array $handler): void;
    public function dispatch(Request $request): Response;

}