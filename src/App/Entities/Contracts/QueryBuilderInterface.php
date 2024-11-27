<?php

namespace App\Entities\Contracts;

interface QueryBuilderInterface
{
    public function setIndex(string $index): self;

    public function addRangeFilter(string $field, $start, $end): self;

    public function addCompositeAggregation(array $composite): self;

    public function addTopHitsAggregation(
        string $name,
        array $fields,
        string $sortField,
        string $sortOrder
    ): self;

    public function addValueCountAggregation(string $name, string $field): self;

    public function build(): array;
}
