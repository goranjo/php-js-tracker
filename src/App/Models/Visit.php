<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "visits")]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    private string $url;

    #[ORM\Column(type: "string")]
    private string $ip;

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $referrer;

    #[ORM\Column(type: "datetime")]
    private \DateTime $timestamp;

    #[ORM\Column(name: "user_agent", type: "string", nullable: true)]
    private ?string $user_agent = null;

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function setReferrer(?string $referrer): void
    {
        $this->referrer = $referrer;
    }

    public function setTimestamp(\DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function setUserAgent(?string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
