<?php

namespace App\Models\Mappers;

use App\Messages\DTO\VisitMessage;
use App\Models\Visit;
use DateTime;
use Exception;

class VisitMapper
{
    /**
     * @throws Exception
     */
    public static function mapTo(VisitMessage $message): Visit
    {
        $visit = new Visit();
        $visit->setUrl($message->url);
        $visit->setIp($message->ip);
        $visit->setReferrer($message->referrer);
        $visit->setTimestamp(new DateTime($message->timestamp));
        $visit->setUserAgent($message->user_agent);

        return $visit;
    }
}
