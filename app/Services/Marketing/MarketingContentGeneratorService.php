<?php

namespace App\Services\Marketing;

use App\Models\MarketingContent;
use App\Models\MarketingContentIdea;
use App\Services\AI\VertexAiService;
use Illuminate\Support\Str;
use RuntimeException;

class MarketingContentGeneratorService
{
    public function __construct(
        protected VertexAiService $vertexAi
    ) {
    }

    public function generate(
        MarketingContentIdea $idea
    ): MarketingContent {
        $idea->load([
            'campaign',
            'audience',
            'painPoint',
            'keyword',
            'contentPillar',
        ]);

        $prompt = $this->buildPrompt($idea);

        $rawResponse = $this->vertexAi->generateContent($prompt);

        $data = $this->parseResponse($rawResponse);

        $content = MarketingContent::create([
            'marketing_content_idea_id' => $idea->id,
            'marketing_campaign_id' => $idea->marketing_campaign_id,

            'title' => $data['title'],
            'slug' => Str::slug($data['title']),

            'content' => $data['content'],
            'excerpt' => $data['excerpt'],

            'seo_title' => $data['seo_title'],
            'seo_description' => $data['seo_description'],
            'seo_keywords' => $data['seo_keywords'],
            'platform_posts' => $data['platform_posts'],
            'distribution_checklist' => $data['distribution_checklist'],
            'ai_prompt_version' => 'v1',

            'generated_at' => now(),
        ]);

        $idea->update(['status' => 'generated']);

        return $content;

================ OUTPUT FORMAT ================

Kembalikan HANYA JSON valid.

Jangan gunakan markdown code fence.
Jangan menambahkan penjelasan sebelum atau sesudah JSON.

Format:

{
  "title": "Judul konten",
  "content": "Konten lengkap berupa HTML dalam Bahasa Indonesia",
  "excerpt": "Ringkasan singkat",
  "seo_title": "SEO title",
  "seo_description": "SEO description",
  "seo_keywords": "keyword 1, keyword 2, keyword 3",
  "platform_posts": {
    "linkedin": {
      "hook": "Kalimat pembuka",
      "caption": "Caption lengkap",
      "hashtags": "#AldefTech #DigitalisasiBisnis",
      "cta": "CTA singkat"
    },
    "facebook": {
      "hook": "Kalimat pembuka",
      "caption": "Caption lengkap",
      "hashtags": "#AldefTech #SoftwareDevelopment",
      "cta": "CTA singkat"
    },
    "instagram": {
      "hook": "Kalimat pembuka",
      "caption": "Caption lengkap",
      "hashtags": "#AldefTech #AIForBusiness",
      "cta": "CTA singkat"
    }
  },
  "distribution_checklist": [
    "Publish artikel di blog Aldef Tech",
    "Bagikan caption LinkedIn",
    "Bagikan caption Facebook",
    "Bagikan ringkasan ke WhatsApp Business atau komunitas relevan"
  ]
}

Pastikan field title, content, excerpt, seo_title, seo_description, seo_keywords berupa string.
Pastikan platform_posts berupa object dan distribution_checklist berupa array string.
PROMPT;
    }

    protected function parseResponse(
        string $rawResponse
    ): array {
        $clean = trim($rawResponse);

        if (str_starts_with($clean, '```')) {
            $clean = preg_replace(
                '/^```(?:json)?\s*/i',
                '',
                $clean
            );

            $clean = preg_replace(
                '/\s*```$/',
                '',
                $clean
            );
        }

        $data = json_decode(
            trim($clean),
            true
        );

        if (!is_array($data)) {
            throw new RuntimeException(
                'Vertex AI returned invalid JSON: ' .
                $rawResponse
            );
        }

        $required = [
            'title',
            'content',
            'excerpt',
            'seo_title',
            'seo_description',
            'seo_keywords',
        ];

        foreach ($required as $field) {
            if (
                !array_key_exists($field, $data) ||
                !is_string($data[$field]) ||
                trim($data[$field]) === ''
            ) {
                throw new RuntimeException(
                    "Vertex AI response is missing required field: {$field}"
                );
            }
        }

        $data['platform_posts'] = $this->normalizePlatformPosts(
            $data['platform_posts'] ?? [],
            $data
        );

        $data['distribution_checklist'] = $this->normalizeChecklist(
            $data['distribution_checklist'] ?? []
        );

        return $data;
    }

    protected function formatPlatformsForPrompt(array $platforms): string
    {
        $platforms = array_values(array_filter($platforms));

        if (empty($platforms)) {
            $platforms = ['linkedin', 'facebook', 'instagram'];
        }

        return implode(', ', $platforms);
    }

    protected function normalizePlatformPosts(mixed $posts, array $data): array
    {
        $cleanPosts = [];

        if (is_array($posts)) {
            foreach ($posts as $platform => $post) {
                if (!is_array($post)) {
                    continue;
                }

                $cleanPosts[(string) $platform] = [
                    'hook' => (string) ($post['hook'] ?? $data['title']),
                    'caption' => (string) ($post['caption'] ?? $data['excerpt']),
                    'hashtags' => (string) ($post['hashtags'] ?? '#AldefTech #DigitalisasiBisnis'),
                    'cta' => (string) ($post['cta'] ?? 'Hubungi Aldef Tech untuk konsultasi.'),
                ];
            }
        }

        if (!empty($cleanPosts)) {
            return $cleanPosts;
        }

        foreach (['linkedin', 'facebook', 'instagram'] as $platform) {
            $cleanPosts[$platform] = [
                'hook' => $data['title'],
                'caption' => Str::limit(strip_tags($data['content']), 420),
                'hashtags' => '#AldefTech #DigitalisasiBisnis #SoftwareDevelopment',
                'cta' => 'Diskusikan kebutuhan sistem bisnis Anda dengan Aldef Tech.',
            ];
        }

        return $cleanPosts;
    }

    protected function normalizeChecklist(mixed $checklist): array
    {
        if (is_array($checklist)) {
            $items = array_values(array_filter(
                $checklist,
                fn ($item) => is_string($item) && trim($item) !== ''
            ));

            if (!empty($items)) {
                return $items;
            }
        }

        return [
            'Review dan approve konten.',
            'Publish artikel di blog Aldef Tech.',
            'Bagikan caption ke LinkedIn, Facebook, Instagram, dan komunitas relevan.',
            'Pantau leads yang masuk dari WhatsApp dan form kontak.',
        ];
    }
}
