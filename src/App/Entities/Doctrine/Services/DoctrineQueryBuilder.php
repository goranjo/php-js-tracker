<?php

namespace App\Entities\Doctrine\Services;

use App\Entities\Contracts\QueryBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineQueryBuilder implements QueryBuilderInterface
{
    private EntityManagerInterface $entityManager;
    private QueryBuilder $queryBuilder;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->queryBuilder = $entityManager->createQueryBuilder();
    }

    public function setIndex(string $index): self
    {
        $this->queryBuilder->from($index, 'm');
        return $this;
    }

    public function addRangeFilter(string $field, $start, $end): self
    {
        $this->queryBuilder->andWhere("m.$field BETWEEN :start AND :end")
            ->setParameter('start', $start)
            ->setParameter('end', $end);
        return $this;
    }

    public function addCompositeAggregation(array $composite): self
    {
        foreach ($composite as $item) {
            $field = key($item);
            $this->queryBuilder->addGroupBy("m.$field");
        }
        return $this;
    }

    public function addTopHitsAggregation(string $name, array $fields, string $sortField, string $sortOrder): self
    {
        $this->queryBuilder->select(array_merge($fields, ["m.$sortField"]))
            ->orderBy("m.$sortField", $sortOrder);
        return $this;
    }

    public function addValueCountAggregation(string $name, string $field): self
    {
        $this->queryBuilder->addSelect("COUNT(m.$field) AS $name");
        return $this;
    }

    public function build(): array
    {
        return $this->queryBuilder->getQuery()->getArrayResult();
    }
}
