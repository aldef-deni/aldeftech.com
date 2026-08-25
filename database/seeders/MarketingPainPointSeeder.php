<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketingPainPointSeeder extends Seeder
{
    public function run(): void
    {
        $audiences = DB::table('marketing_audiences')
            ->pluck('id', 'slug');

        $data = [
            ['business-owner-director', 'Proses bisnis masih menggunakan Excel dan pekerjaan manual', 'Produktivitas rendah dan human error meningkat.', 'Custom business system dan workflow automation.', 100],
            ['business-owner-director', 'Tidak memiliki dashboard bisnis real-time', 'Keputusan bisnis terlambat dan sulit melakukan monitoring.', 'Executive dashboard dan business intelligence.', 95],
            ['it-manager-it-department', 'Legacy system sulit dikembangkan', 'Development lambat dan biaya maintenance tinggi.', 'Legacy modernization dan architecture improvement.', 100],
            ['it-manager-it-department', 'Sistem tidak saling terintegrasi', 'Data terfragmentasi dan input ulang meningkat.', 'API integration dan centralized data architecture.', 95],
            ['operations-manager', 'Approval dan workflow masih manual', 'Proses lambat dan sulit dilacak.', 'Workflow automation dan approval system.', 95],
            ['sales-business-manager', 'Leads banyak tetapi follow-up tidak konsisten', 'Conversion rate rendah dan peluang revenue hilang.', 'CRM dan automated lead follow-up.', 100],
            ['startup-product-founder', 'Sulit menemukan engineering partner untuk membangun MVP', 'Product launch terlambat dan biaya development tidak terkontrol.', 'MVP development dan product engineering.', 90],
            ['hospitality-retail-distribution', 'Data antar cabang tidak terintegrasi', 'Kontrol pusat rendah dan reporting lambat.', 'Multi-branch centralized system.', 90],
        ];

        foreach ($data as [$audience, $title, $impact, $solution, $priority]) {
            $audienceId = $audiences[$audience] ?? null;

            if (!$audienceId) {
                continue;
            }

            DB::table('marketing_pain_points')->updateOrInsert(
                [
                    'marketing_audience_id' => $audienceId,
                    'title' => $title,
                ],
                [
                    'description' => $impact,
                    'severity' => 'high',
                    'business_impact' => $impact,
                    'desired_solution' => $solution,
                    'is_active' => true,
                    'priority' => $priority,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->command?->info('Marketing pain points seeded: ' . count($data));
    }
}
