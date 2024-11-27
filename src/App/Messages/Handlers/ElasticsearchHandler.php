<?php

namespace App\Messages\Handlers;

use App\Messages\DTO\VisitMessage;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class ElasticsearchHandler
{
    private Client $elasticClient;

    public function __construct(Client $elasticClient)
    {
        $this->elasticClient = $elasticClient;
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function __invoke(VisitMessage $message): void
    {
        $geoLocation = $this->getGeoLocation($message->ip);
        $browserDetails = $this->parseUserAgent($message->user_agent);

        $this->elasticClient->index([
            'index' => 'analytics',
            'body' => [
                'url' => $message->url,
                'ip' => $message->ip,
                'geoLocation' => $geoLocation,
                'browser' => $browserDetails,
                'referrer' => $message->referrer,
                'timestamp' => $message->timestamp,
            ],
        ]);
    }

    private function getGeoLocation(string $ip): array
    {
        // Mocked geo-location data
        return [
            'country' => 'Macedonia',
            'region' => 'Pelagonia',
            'city' => 'Bitolaaaa',
        ];
    }

    private function parseUserAgent(string $userAgent): array
    {
        return [
            'browser' => 'Chrome',
            'version' => '105.0.0',
            'os' => 'Windows 10',
        ];
    }
}
