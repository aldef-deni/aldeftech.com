<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketingContentPillarSeeder extends Seeder
{
    public function run(): void
    {
        $pillars = [
            [
                'name' => 'AI & Business Automation',
                'slug' => 'ai-business-automation',
                'description' => 'AI, AI Agent, workflow automation, LLM, RAG, computer vision, dan penerapan AI untuk bisnis.',
                'objectives' => 'Membangun authority Aldef Tech sebagai AI technology partner.',
                'content_types' => 'article,linkedin,facebook,instagram,video',
                'funnel_stage' => 'awareness',
                'default_priority' => 100,
                'default_frequency' => 3,
            ],
            [
                'name' => 'Custom Software Development',
                'slug' => 'custom-software-development',
                'description' => 'Pembuatan sistem custom, web application, enterprise software, dan software engineering.',
                'objectives' => 'Mendatangkan calon klien yang membutuhkan software custom.',
                'content_types' => 'article,linkedin,facebook,instagram',
                'funnel_stage' => 'consideration',
                'default_priority' => 95,
                'default_frequency' => 3,
            ],
            [
                'name' => 'SaaS & Product Engineering',
                'slug' => 'saas-product-engineering',
                'description' => 'SaaS, MVP, multi-tenancy, architecture, subscription billing, dan product development.',
                'objectives' => 'Menarik startup founder dan perusahaan yang ingin membangun SaaS.',
                'content_types' => 'article,linkedin,facebook,video',
                'funnel_stage' => 'consideration',
                'default_priority' => 90,
                'default_frequency' => 2,
            ],
            [
                'name' => 'Business Digitalization',
                'slug' => 'business-digitalization',
                'description' => 'Transformasi digital, workflow, ERP, CRM, inventory, POS, dashboard, dan operational systems.',
                'objectives' => 'Membangun awareness terhadap kebutuhan digitalisasi bisnis.',
                'content_types' => 'article,facebook,instagram,linkedin',
                'funnel_stage' => 'awareness',
                'default_priority' => 90,
                'default_frequency' => 3,
            ],
            [
                'name' => 'System Integration & Engineering',
                'slug' => 'system-integration-engineering',
                'description' => 'API, system integration, legacy modernization, database, cloud, security, dan performance.',
                'objectives' => 'Membangun technical authority dan menarik IT decision maker.',
                'content_types' => 'article,linkedin,facebook',
                'funnel_stage' => 'consideration',
                'default_priority' => 80,
                'default_frequency' => 2,
            ],
            [
                'name' => 'Case Study & Portfolio',
                'slug' => 'case-study-portfolio',
                'description' => 'Studi kasus, project showcase, architecture breakdown, hasil implementasi, dan portfolio Aldef Tech.',
                'objectives' => 'Membangun trust dan membuktikan kemampuan engineering.',
                'content_types' => 'article,linkedin,facebook,instagram,video',
                'funnel_stage' => 'decision',
                'default_priority' => 100,
                'default_frequency' => 2,
            ],
            [
                'name' => 'Business Problems & Solutions',
                'slug' => 'business-problems-solutions',
                'description' => 'Masalah nyata bisnis dan bagaimana teknologi dapat menyelesaikannya.',
                'objectives' => 'Mengubah pain point audience menjadi demand terhadap solusi Aldef Tech.',
                'content_types' => 'article,facebook,linkedin,instagram,video',
                'funnel_stage' => 'awareness',
                'default_priority' => 100,
                'default_frequency' => 3,
            ],
            [
                'name' => 'Conversion & Offer',
                'slug' => 'conversion-offer',
                'description' => 'Konten consultation, audit, discovery session, dan project inquiry.',
                'objectives' => 'Menghasilkan qualified leads dan project opportunity.',
                'content_types' => 'facebook,instagram,linkedin,article',
                'funnel_stage' => 'decision',
                'default_priority' => 85,
                'default_frequency' => 1,
            ],
        ];

        foreach ($pillars as $pillar) {
            DB::table('marketing_content_pillars')->updateOrInsert(
                ['slug' => $pillar['slug']],
                [
                    'name' => $pillar['name'],
                    'description' => $pillar['description'],
                    'objectives' => $pillar['objectives'],
                    'content_types' => $pillar['content_types'],
                    'funnel_stage' => $pillar['funnel_stage'],
                    'default_priority' => $pillar['default_priority'],
                    'default_frequency' => $pillar['default_frequency'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command?->info(
            'Marketing content pillars seeded: ' . count($pillars)
        );
    }
}
