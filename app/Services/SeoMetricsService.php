<?php

namespace App\Services;

class SeoMetricsService
{
    // Replace through the service container when a real provider is integrated.
    // A site verification token is not access to Search Console reporting data.
    public function authority(string $domain): array
    {
        return ['authority_score' => null, 'traffic_score' => null, 'metrics_provider' => null];
    }

    public function searchConsole(string $url): array
    {
        return ['available' => false, 'impressions' => null, 'clicks' => null,
            'ctr' => null, 'average_position' => null, 'queries' => null];
    }
}
