<?php

namespace App\Services\Marketing;

use App\Models\MarketingAudience;
use App\Models\MarketingCampaign;
use App\Models\MarketingContentIdea;
use App\Models\MarketingContentPillar;
use App\Models\MarketingKeyword;
use App\Models\MarketingPainPoint;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MarketingIntelligenceService
{
    public function generateIdeas(
        MarketingCampaign $campaign,
        int $limit = 10
    ): Collection {
        $audienceIds = $campaign->target_audiences ?? [];

        $audiences = MarketingAudience::query()
            ->where('is_active', true)
            ->when(
                !empty($audienceIds),
                fn ($query) => $query->whereIn('id', $audienceIds)
            )
            ->orderByDesc('priority')
            ->get();

        $pillars = MarketingContentPillar::query()
            ->where('is_active', true)
            ->orderByDesc('default_priority')
            ->get();

        $ideas = collect();

        foreach ($audiences as $audience) {
            $painPoints = MarketingPainPoint::query()
                ->where('marketing_audience_id', $audience->id)
                ->where('is_active', true)
                ->orderByDesc('priority')
                ->get();

            $keywords = MarketingKeyword::query()
                ->where('is_active', true)
                ->where(function ($query) use ($audience) {
                    $query
                        ->where('marketing_audience_id', $audience->id)
                        ->orWhereNull('marketing_audience_id');
                })
                ->orderByDesc('priority')
                ->get();

            foreach ($painPoints as $painPoint) {
                $keyword = $keywords->first();

                foreach ($pillars as $pillar) {
                    if ($ideas->count() >= $limit) {
                        break 3;
                    }

                    $title = $this->buildTitle(
                        $audience,
                        $painPoint,
                        $pillar
                    );

                    $platforms = $this->resolvePlatforms(
                        $campaign,
                        $pillar
                    );

                    $idea = MarketingContentIdea::firstOrCreate(
                        [
                            'marketing_campaign_id' => $campaign->id,
                            'title' => $title,
                        ],
                        [
                            'marketing_audience_id' => $audience->id,
                            'marketing_pain_point_id' => $painPoint->id,
                            'marketing_keyword_id' => $keyword?->id,
                            'marketing_content_pillar_id' => $pillar->id,
                            'hook' => $this->buildHook(
                                $audience,
                                $painPoint
                            ),
                            'brief' => $this->buildBrief(
                                $audience,
                                $painPoint,
                                $keyword,
                                $pillar
                            ),
                            'content_type' => $this->resolveContentType(
                                $pillar
                            ),
                            'funnel_stage' => $pillar->funnel_stage,
                            'status' => 'idea',
                            'priority' => $this->calculatePriority(
                                $audience,
                                $painPoint,
                                $pillar
                            ),
                            'platforms' => $platforms,
                            'cta' => $this->buildCta(
                                $pillar
                            ),
                        ]
                    );

                    $ideas->push($idea);
                }
            }
        }

        return $ideas;
    }

    protected function buildTitle(
        MarketingAudience $audience,
        MarketingPainPoint $painPoint,
        MarketingContentPillar $pillar
    ): string {
        return match ($pillar->slug) {
            'business-digitalization' =>
                'Masih Menggunakan Cara Manual? Saatnya Digitalisasi Bisnis',

            'ai-business-automation' =>
                'Bagaimana AI dan Automation Mengurangi Pekerjaan Manual di Bisnis',

            'custom-software-development' =>
                'Kapan Bisnis Membutuhkan Custom Software Development?',

            'saas-product-engineering' =>
                'Dari Ide Menjadi SaaS: Apa yang Harus Dipersiapkan?',

            'system-integration-engineering' =>
                'Mengapa Sistem Bisnis Harus Saling Terintegrasi?',

            'case-study-portfolio' =>
                'Studi Kasus: Mengubah Masalah Operasional Menjadi Sistem Digital',

            'business-problems-solutions' =>
                'Masalah Bisnis yang Sering Terjadi dan Bagaimana Teknologi Menyelesaikannya',

            'conversion-offer' =>
                'Ingin Digitalisasi Bisnis? Mulai dari Business System Audit',

            default =>
                $painPoint->title,
        };
    }

    protected function buildHook(
        MarketingAudience $audience,
        MarketingPainPoint $painPoint
    ): string {
        return sprintf(
            '%s sering menjadi masalah bagi %s. Jika kondisi ini terjadi di perusahaan Anda, mungkin sudah waktunya proses tersebut diubah menjadi sistem yang lebih terstruktur.',
            $painPoint->title,
            $audience->name
        );
    }

    protected function buildBrief(
        MarketingAudience $audience,
        MarketingPainPoint $painPoint,
        ?MarketingKeyword $keyword,
        MarketingContentPillar $pillar
    ): string {
        $keywordText = $keyword?->keyword ?? 'digitalisasi bisnis';

        return sprintf(
            'Buat konten untuk %s dengan membahas masalah "%s", dampaknya terhadap bisnis, pendekatan solusi berdasarkan pillar "%s", dan kaitkan secara natural dengan keyword "%s". Fokus pada edukasi dan relevansi bisnis, bukan hard selling.',
            $audience->name,
            $painPoint->title,
            $pillar->name,
            $keywordText
        );
    }

    protected function resolveContentType(
        MarketingContentPillar $pillar
    ): string {
        $types = collect(
            array_filter(
                array_map(
                    'trim',
                    explode(',', (string) $pillar->content_types)
                )
            )
        );

        return $types->first() ?? 'article';
    }

    protected function resolvePlatforms(
        MarketingCampaign $campaign,
        MarketingContentPillar $pillar
    ): array {
        if (!empty($campaign->platforms)) {
            return array_values($campaign->platforms);
        }

        return array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', (string) $pillar->content_types)
                ),
                fn ($type) => in_array(
                    $type,
                    [
                        'facebook',
                        'instagram',
                        'linkedin',
                        'youtube',
                        'tiktok',
                    ],
                    true
                )
            )
        );
    }

    protected function calculatePriority(
        MarketingAudience $audience,
        MarketingPainPoint $painPoint,
        MarketingContentPillar $pillar
    ): int {
        return min(
            100,
            (int) round(
                (
                    $audience->priority +
                    $painPoint->priority +
                    $pillar->default_priority
                ) / 3
            )
        );
    }

    protected function buildCta(
        MarketingContentPillar $pillar
    ): string {
        return match ($pillar->funnel_stage) {
            'awareness' =>
                'Follow Aldef Tech untuk insight teknologi dan digitalisasi bisnis.',

            'consideration' =>
                'Diskusikan kebutuhan sistem bisnis Anda dengan Aldef Tech.',

            'decision' =>
                'Hubungi Aldef Tech untuk konsultasi dan discovery session.',

            default =>
                'Pelajari bagaimana teknologi dapat membantu bisnis Anda.',
        };
    }
}
