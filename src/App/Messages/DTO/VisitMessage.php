<?php

namespace App\Messages\DTO;

class VisitMessage
{
    public function __construct(
        public string $url,
        public string $ip,
        public ?string $referrer,
        public ?string $user_agent,
        public string $timestamp
    ) {}
}
