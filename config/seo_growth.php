<?php

return [
    'enabled' => env('SEO_GROWTH_ENABLED', true),
    'backlink_prospect_limit' => (int) env('SEO_BACKLINK_PROSPECT_LIMIT', 5),
    'content_opportunity_limit' => (int) env('SEO_CONTENT_OPPORTUNITY_LIMIT', 5),
    'refresh_limit' => (int) env('SEO_REFRESH_LIMIT', 3),
    'article_limit' => (int) env('SEO_ARTICLE_LIMIT', 2),
    'internal_link_limit' => (int) env('SEO_INTERNAL_LINK_LIMIT', 3),
    'daily_api_limit' => (int) env('SEO_DAILY_API_LIMIT', 8),
    'grounding_enabled' => env('SEO_GROUNDING_ENABLED', true),
    'review_days' => 90,
];
