<?php

namespace App\Messages\Handlers;

use App\Messages\DTO\VisitMessage;
use App\Models\Mappers\VisitMapper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MySQLHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(VisitMessage $message): void
    {
        $visit = VisitMapper::mapTo($message);

        $this->entityManager->persist($visit);
        $this->entityManager->flush();
    }
}

