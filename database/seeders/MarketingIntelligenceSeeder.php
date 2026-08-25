<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingAudience;

class MarketingIntelligenceSeeder extends Seeder
{
    public function run(): void
    {
        $audiences = [
            [
                'name' => 'Business Owner & Director',
                'slug' => 'business-owner-director',
                'industry' => 'SME, Enterprise, Professional Services, Manufacturing, Distributor',
                'company_size' => 'SME to Enterprise',
                'decision_maker' => 'Owner, Director, CEO',
                'description' => 'Pemilik dan pimpinan perusahaan yang membutuhkan digitalisasi proses bisnis dan sistem yang sesuai kebutuhan perusahaan.',
                'goals' => 'Meningkatkan efisiensi, mengurangi pekerjaan manual, meningkatkan kontrol bisnis, dan mendapatkan data yang lebih akurat.',
                'needs' => 'Custom software, ERP, CRM, business automation, dashboard, AI automation.',
                'priority' => 100,
            ],
            [
                'name' => 'IT Manager & IT Department',
                'slug' => 'it-manager-it-department',
                'industry' => 'All Industries',
                'company_size' => 'SME to Enterprise',
                'decision_maker' => 'IT Manager, CTO, Head of IT',
                'description' => 'Tim teknologi yang membutuhkan partner engineering untuk membangun, mengintegrasikan, memodernisasi, atau mengoptimalkan sistem.',
                'goals' => 'Meningkatkan reliability, scalability, security, integration, dan development velocity.',
                'needs' => 'System integration, API development, legacy modernization, cloud application, security, performance optimization.',
                'priority' => 95,
            ],
            [
                'name' => 'Operations Manager',
                'slug' => 'operations-manager',
                'industry' => 'Manufacturing, Distributor, Retail, Hospitality, Services',
                'company_size' => 'SME to Enterprise',
                'decision_maker' => 'Operations Manager, General Manager, COO',
                'description' => 'Pengelola operasional yang membutuhkan sistem untuk mengurangi pekerjaan manual dan meningkatkan kontrol proses.',
                'goals' => 'Automasi workflow, mengurangi human error, mempercepat proses, dan mendapatkan real-time visibility.',
                'needs' => 'Workflow automation, inventory system, approval system, dashboard, reporting, operational software.',
                'priority' => 90,
            ],
            [
                'name' => 'Sales & Business Manager',
                'slug' => 'sales-business-manager',
                'industry' => 'B2B, Retail, Services, Distribution',
                'company_size' => 'SME to Enterprise',
                'decision_maker' => 'Sales Manager, Business Development Manager, Commercial Director',
                'description' => 'Tim sales dan business development yang membutuhkan sistem untuk mengelola leads, pipeline, follow-up, dan conversion.',
                'goals' => 'Meningkatkan conversion rate, mempercepat follow-up, dan meningkatkan visibility pipeline.',
                'needs' => 'CRM, lead management, WhatsApp automation, sales dashboard, pipeline automation.',
                'priority' => 85,
            ],
            [
                'name' => 'Startup & Product Founder',
                'slug' => 'startup-product-founder',
                'industry' => 'Technology, SaaS, Digital Business',
                'company_size' => 'Startup',
                'decision_maker' => 'Founder, CTO, Product Manager',
                'description' => 'Founder dan product team yang membutuhkan partner engineering untuk membangun MVP, SaaS, atau digital product.',
                'goals' => 'Launch product lebih cepat, validasi market, scale architecture, dan membangun recurring revenue.',
                'needs' => 'MVP development, SaaS development, multi-tenant architecture, API, AI product.',
                'priority' => 80,
            ],
            [
                'name' => 'Hospitality, Retail & Distribution Business',
                'slug' => 'hospitality-retail-distribution',
                'industry' => 'Hospitality, Retail, Distribution',
                'company_size' => 'SME to Enterprise',
                'decision_maker' => 'Owner, General Manager, Operations Manager',
                'description' => 'Bisnis dengan kebutuhan operasional kompleks seperti inventory, POS, reservation, warehouse, dan multi-branch.',
                'goals' => 'Mengintegrasikan operasional, meningkatkan kontrol cabang, dan mendapatkan data bisnis real-time.',
                'needs' => 'POS, inventory, warehouse, reservation, ERP, multi-branch management.',
                'priority' => 75,
            ],
        ];

        foreach ($audiences as $data) {
            MarketingAudience::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command?->info('Marketing audiences seeded: ' . count($audiences));
    }
}
