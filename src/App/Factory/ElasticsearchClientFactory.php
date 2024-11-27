<?php

namespace App\Factory;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;

class ElasticsearchClientFactory
{
    /**
     * @throws AuthenticationException
     */
    public static function create(): Client
    {
        return ClientBuilder::create()
            ->setHosts(['http://elasticsearch:9200'])
            ->build();
    }
}
