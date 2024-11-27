<?php

namespace App\Http\Controllers;

use App\Entities\Contracts\QueryBuilderInterface;
use App\Http\Controllers\Validators\AnalyticsValidator;
use App\Http\Resources\UniqueClickResource;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsController extends Controller
{
    private AnalyticsValidator $validator;

    private QueryBuilderInterface $queryBuilder;

    public function __construct(AnalyticsValidator $validator = null, QueryBuilderInterface $queryBuilder)
    {
        parent::__construct(null);
        $this->validator = $validator;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @throws AuthenticationException
     */
    public function getAnalytics(Request $request): Response
    {
        $validationResult = $this->validator->validate($request);

        if ($validationResult instanceof Response) {
            return new Response($validationResult);
        }

        $startDate = $validationResult['start_date'];
        $endDate = $validationResult['end_date'];

        $client = ClientBuilder::create()->setHosts(['elasticsearch:9200'])->build();

        $params = $this->queryBuilder
            ->setIndex('analytics')
            ->addRangeFilter('timestamp', $startDate, $endDate)
            ->addCompositeAggregation(
                [
                    ['ip' => ['terms' => ['field' => 'ip.keyword']]],
                    ['url' => ['terms' => ['field' => 'url.keyword']]],
                ]
            )
            ->addTopHitsAggregation('latest_event', ['url', 'ip', 'referrer', 'userAgent', 'timestamp', 'geoLocation', 'browser'], 'timestamp', 'desc')
            ->addValueCountAggregation('click_count', 'url.keyword')
            ->build();

        try {
            $results = $client->search($params)['aggregations']['unique_ip_url']['buckets'] ?? [];

            $uniqueClicks = UniqueClickResource::collection($results);

            return new Response(json_encode($uniqueClicks), 200, ['Content-Type' => 'application/json']);
        } catch (Exception $e) {
            return new Response(json_encode(['error' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
        }
    }
}
