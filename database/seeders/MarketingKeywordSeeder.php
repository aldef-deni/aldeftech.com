<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketingKeywordSeeder extends Seeder
{
    public function run(): void
    {
        $keywords = [
            ['jasa pembuatan aplikasi', 'commercial', 'service', 100],
            ['jasa pembuatan sistem informasi', 'commercial', 'service', 100],
            ['custom software development', 'commercial', 'service', 95],
            ['jasa pembuatan software', 'commercial', 'service', 95],
            ['jasa pembuatan website perusahaan', 'commercial', 'service', 90],
            ['jasa pembuatan ERP', 'commercial', 'service', 90],
            ['jasa pembuatan CRM', 'commercial', 'service', 90],
            ['jasa pembuatan SaaS', 'commercial', 'service', 90],
            ['AI automation Indonesia', 'commercial', 'ai', 100],
            ['AI agent untuk bisnis', 'commercial', 'ai', 100],
            ['AI customer service', 'commercial', 'ai', 90],
            ['business process automation', 'commercial', 'automation', 95],
            ['software development company Indonesia', 'commercial', 'brand', 90],
            ['jasa integrasi sistem', 'commercial', 'integration', 90],
            ['jasa integrasi API', 'commercial', 'integration', 85],
            ['sistem inventory', 'commercial', 'solution', 85],
            ['sistem POS', 'commercial', 'solution', 85],
            ['sistem manajemen gudang', 'commercial', 'solution', 85],
            ['aplikasi absensi karyawan', 'commercial', 'solution', 80],
            ['digitalisasi bisnis', 'informational', 'education', 90],
            ['cara membuat aplikasi bisnis', 'informational', 'education', 75],
            ['cara membuat SaaS', 'informational', 'education', 75],
            ['manfaat AI untuk bisnis', 'informational', 'education', 90],
            ['cara automasi bisnis', 'informational', 'education', 85],
        ];

        foreach ($keywords as [$keyword, $intent, $type, $priority]) {
            DB::table('marketing_keywords')->updateOrInsert(
                ['keyword' => $keyword],
                [
                    'search_intent' => $intent,
                    'keyword_type' => $type,
                    'is_active' => true,
                    'priority' => $priority,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command?->info(
            'Marketing keywords seeded: ' . count($keywords)
        );
    }
}
