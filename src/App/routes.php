<?php

use App\Core\Middlewares\CorsMiddleware;
use App\Core\Router;
use App\Core\RouteResolver;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TrackerController;
use Symfony\Component\HttpFoundation\Response;

$router = new Router(new RouteResolver($container ?? null));

$router->addMiddleware(new CorsMiddleware());

$router->get('/', function () {
    return new Response('Welcome to My Traffic Tracker');
});

$router->post('/api/track', [TrackerController::class, 'track']);
$router->options('/api/track', function () {
    return new Response('', 204);
});

$router->get('/api/analytics', [AnalyticsController::class, 'getAnalytics']);
$router->options('/api/analytics', function () {
    return new Response('', 204);
});
