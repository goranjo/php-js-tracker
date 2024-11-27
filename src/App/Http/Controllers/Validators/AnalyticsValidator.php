<?php

namespace App\Http\Controllers\Validators;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsValidator
{
    /**
     * Validates the analytics request for required parameters.
     *
     * @param Request $request
     * @return array|Response
     */
    public function validate(Request $request): array|Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        if (!$startDate || !$endDate) {
            return new Response(
                json_encode(['error' => 'start_date and end_date are required']),
                400,
                ['Content-Type' => 'application/json']
            );
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
