<?php

namespace App\Core\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouteResolverInterface
{
    public function resolve(mixed $handler, Request $request): Response;
}
