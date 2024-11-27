<?php

namespace Integration;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use PHPUnit\Framework\TestCase;

class ElasticsearchConnectionTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();
    }

    public function testElasticsearchConnection(): void
    {
        $response = $this->client->info();

        $this->assertArrayHasKey('version', $response, 'Elasticsearch response does not contain version info');
        $this->assertNotEmpty($response['version']['number'], 'Elasticsearch version number is empty');
    }

    public function testClusterHealth(): void
    {
        $response = $this->client->cluster()->health();

        $this->assertArrayHasKey('status', $response, 'Cluster health response does not contain status');
        $this->assertContains(
            $response['status'],
            ['green', 'yellow', 'red'],
            'Cluster health status is invalid'
        );
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function testCreateAndDeleteIndex(): void
    {
        $indexName = 'test_index';

        $response = $this->client->indices()->create(['index' => $indexName]);
        $this->assertTrue($response['acknowledged'], 'Index creation was not acknowledged');

        $exists = $this->client->indices()->exists(['index' => $indexName]);
        $this->assertTrue((bool)$exists, 'Index does not exist after creation');

        $response = $this->client->indices()->delete(['index' => $indexName]);
        $this->assertTrue($response['acknowledged'], 'Index deletion was not acknowledged');
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function testIndexDocument(): void
    {
        $indexName = 'test_index';
        $documentId = '1';
        $document = [
            'title' => 'Test Document',
            'content' => 'This is a test document.',
        ];

        $this->client->indices()->create(['index' => $indexName]);

        $response = $this->client->index([
            'index' => $indexName,
            'id'    => $documentId,
            'body'  => $document,
        ]);

        $this->assertEquals($documentId, $response['_id'], 'Document was not indexed with the correct ID');

        $response = $this->client->get([
            'index' => $indexName,
            'id'    => $documentId,
        ]);

        $this->assertArrayHasKey('_source', $response, 'Retrieved document does not contain _source');
        $this->assertEquals($document, $response['_source'], 'Retrieved document content does not match');

        $this->client->indices()->delete(['index' => $indexName]);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function testFailingCase(): void
    {
        $response = $this->client->cluster()->health();

        $this->assertEquals('purple', $response['status'], 'Expected cluster status to be purple, but it never is!');
    }
}
