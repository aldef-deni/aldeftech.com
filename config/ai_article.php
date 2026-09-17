<?php

/*
|--------------------------------------------------------------------------
| AI Article Publication
|--------------------------------------------------------------------------
| A generated article is validated by App\Services\ArticlePublishGuard and
| then either published or kept as a draft. Nothing reaches /blog, the
| homepage Insights block or the sitemap without passing that gate.
|
| AI_ARTICLE_AUTO_PUBLISH=false keeps every generated article as a draft that
| an editor must review and publish by hand.
| AI_ARTICLE_MIN_WORDS is the minimum amount of prose a generated article must
| carry before it may be published unattended.
*/

return [
    'auto_publish' => env('AI_ARTICLE_AUTO_PUBLISH', true),

    'min_words' => (int) env('AI_ARTICLE_MIN_WORDS', 350),
];
