<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class Controller
{
    protected ?MessageBusInterface $messageBus;

    public function __construct(?MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function options(Request $request): Response
    {
        return $this->createCorsResponse(new Response('', 204));
    }

    protected function createCorsResponse(Response $response): Response
    {
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
