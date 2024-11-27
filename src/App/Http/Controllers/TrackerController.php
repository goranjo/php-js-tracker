<?php

namespace App\Http\Controllers;

use App\Messages\DTO\VisitMessage;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\TransportException;

class TrackerController extends Controller
{
    //todo do request validation
    public function track(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['url'])) {
            return new Response('Invalid data', 400);
        }

        $message = new VisitMessage(
            $data['url'],
            $request->getClientIp(),
            $data['referrer'] ?? null,
            $request->headers->get('User-Agent'),
            $data['timestamp'] ?? (new DateTime())->format('c')
        );

        try {
            $this->messageBus->dispatch($message);
        } catch (TransportException $e) {
            return new Response('Error: Unable to dispatch message.', 500);
        }

        return new Response('Visit tracked', 200);
    }
}
