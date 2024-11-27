<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use Symfony\Component\HttpFoundation\Request;

$container = require __DIR__ . '/../src/bootstrap.php';
$router = $container->get(Router::class);

require __DIR__ . '/../src/App/routes.php';

$request = Request::createFromGlobals();
$response = $router->dispatch($request);

$response->send();
