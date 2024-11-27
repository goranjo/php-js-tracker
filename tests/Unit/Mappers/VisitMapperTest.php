<?php

namespace Unit\Mappers;

use App\Models\Mappers\VisitMapper;
use App\Messages\DTO\VisitMessage;
use PHPUnit\Framework\TestCase;

class VisitMapperTest extends TestCase
{
    public function testMapTo(): void
    {
        $message = new VisitMessage('http://example.com', '127.0.0.1', 'http://referrer.com', 'Mozilla/5.0', '2024-11-24T14:00:00Z');
        $visit = VisitMapper::mapTo($message);

        $this->assertEquals('http://example.com', $visit->getUrl());
        $this->assertEquals('127.0.0.1', $visit->getIp());
        $this->assertEquals('http://referrer.com', $visit->getReferrer());
        $this->assertEquals('Mozilla/5.0', $visit->getUserAgent());
    }
}
