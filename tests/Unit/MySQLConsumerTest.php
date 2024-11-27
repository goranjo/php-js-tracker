<?php

declare(strict_types=1);

namespace Tests\Consumers;

use App\Consumers\MySQLConsumer;
use App\Models\Visit;
use Doctrine\ORM\EntityManager;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;

class MySQLConsumerTest extends TestCase
{
    public function testConsumeMessage(): void
    {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($entity) {
                return $entity instanceof Visit && $entity->getUrl() === 'https://example.com';
            }));

        $entityManager->expects($this->once())
            ->method('flush');

        $messageData = json_encode([
            'url' => 'https://example.com',
            'ip' => '127.0.0.1',
            'referrer' => 'https://google.com',
            'userAgent' => 'Mozilla/5.0',
            'timestamp' => '2024-11-22T12:00:00Z',
        ]);
        $amqpMessage = new AMQPMessage($messageData);

        $consumer = new MySQLConsumer($entityManager);

        $consumer->processMessage($amqpMessage);

        $this->assertTrue(true);
    }
}
