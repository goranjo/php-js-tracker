<?php

namespace App\Entities\ElasticSearch\Services;

use App\Entities\Contracts\QueryBuilderInterface;

class QueryParamsBuilder implements QueryBuilderInterface
{
    private array $params = [];

    public function setIndex(string $index): self
    {
        $this->params['index'] = $index;
        return $this;
    }

    public function addRangeFilter(string $field, $start, $end): self
    {
        $this->params['body']['query']['bool']['filter'][] = [
            'range' => [
                $field => [
                    'gte' => $start,
                    'lte' => $end,
                ],
            ],
        ];
        return $this;
    }

    public function addCompositeAggregation(array $composite): self
    {
        $this->params['body']['aggs']['unique_ip_url']['composite']['sources'] = $composite;
        return $this;
    }

    public function addTopHitsAggregation(string $name, array $fields, string $sortField, string $sortOrder): self
    {
        $this->params['body']['aggs']['unique_ip_url']['aggs'][$name] = [
            'top_hits' => [
                '_source' => ['includes' => $fields],
                'sort' => [[$sortField => ['order' => $sortOrder]]],
            ],
        ];
        return $this;
    }

    public function addValueCountAggregation(string $name, string $field): self
    {
        $this->params['body']['aggs']['unique_ip_url']['aggs'][$name] = [
            'value_count' => ['field' => $field],
        ];
        return $this;
    }

    public function build(): array
    {
        return $this->params;
    }
}

