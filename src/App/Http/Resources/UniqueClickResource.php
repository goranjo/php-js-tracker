<?php

namespace App\Http\Resources;

class UniqueClickResource
{
    private array $bucket;

    public function __construct(array $bucket)
    {
        $this->bucket = $bucket;
    }

    public function toArray(): array
    {
        $source = $this->bucket['latest_event']['hits']['hits'][0]['_source'] ?? [];

        return [
            'url' => $source['url'] ?? null,
            'ip' => $source['ip'] ?? null,
            'referrer' => $source['referrer'] ?? null,
            'userAgent' => $source['userAgent'] ?? null,
            'geoLocation' => $source['geoLocation'] ?? null,
            'browser' => $source['browser'] ?? null,
            'timestamp' => $source['timestamp'] ?? null,
            'total_clicks' => $this->bucket['doc_count'] ?? 0,
        ];
    }

    public static function collection(array $buckets): array
    {
        return array_map(function ($bucket) {
            return (new self($bucket))->toArray();
        }, $buckets);
    }
}
