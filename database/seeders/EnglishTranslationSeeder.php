<?php

namespace Database\Seeders;

use App\Models\CeoProfile;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Solution;
use Illuminate\Database\Seeder;

/**
 * English copy for the content that ships with the site.
 *
 * Records are matched on their slug (or another stable key) rather than on an
 * auto-increment id, so re-running this after content has been re-seeded still
 * lands on the right rows. Anything not listed here simply keeps falling back
 * to Indonesian, which is the intended behaviour for content the client adds
 * later from the admin.
 */
class EnglishTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $this->services();
        $this->solutions();
        $this->processSteps();
        $this->portfolios();
        $this->faqs();
        $this->ceoProfile();
        $this->siteSettings();

        $this->command?->info('English translations applied.');
    }

    private function apply($model, array $fields): void
    {
        if (! $model) {
            return;
        }

        $model->setTranslations('en', $fields);
        $model->save();
    }

    private function services(): void
    {
        $map = [
            'custom-software-development' => [
                'title' => 'Custom Software Development',
                'short_description' => 'Systems built around your own business process, from requirements analysis through to deployment.',
                'features' => ['Business Process Analysis', 'Custom Architecture', 'Full-stack Development', 'Testing & QA', 'Deployment & Support'],
            ],
            'web-application' => [
                'title' => 'Web Application',
                'short_description' => 'Professional, scalable web applications for modern business. Responsive, fast, and secure.',
                'features' => ['Responsive Design', 'Modern Tech Stack', 'API-first Architecture', 'Performance Optimised'],
            ],
            'saas-development' => [
                'title' => 'SaaS Development',
                'short_description' => 'Building SaaS from MVP to production — multi-tenant, scalable, and market-ready.',
                'features' => ['Multi-tenant Architecture', 'Subscription Billing', 'User Management', 'Analytics Dashboard'],
            ],
            'ai-development' => [
                'title' => 'AI Development',
                'short_description' => 'AI integration, chatbots, AI agents, knowledge bases, and automation that make teams measurably faster.',
                'features' => ['AI Chatbot', 'AI Agent', 'Knowledge Base', 'Natural Language Processing', 'Predictive Analytics'],
            ],
            'business-automation' => [
                'title' => 'Business Automation',
                'short_description' => 'Cutting out repetitive manual work through efficient, reliable process automation.',
                'features' => ['Workflow Automation', 'Data Processing', 'Report Generation', 'Integration'],
            ],
            'system-integration' => [
                'title' => 'System Integration',
                'short_description' => 'Connecting separate systems so data flows seamlessly across every platform you run.',
                'features' => ['API Development', 'Third-party Integration', 'Data Sync', 'Microservices'],
            ],
            'website-development' => [
                'title' => 'Website Development',
                'short_description' => 'Company profiles, landing pages, and corporate sites that are fast and search-friendly.',
                'features' => ['SEO Ready', 'Fast Loading', 'Mobile First', 'CMS Included'],
            ],
            'it-consulting' => [
                'title' => 'IT Consulting',
                'short_description' => 'Technology direction and architecture guidance grounded in how your business actually operates.',
                'features' => ['Technology Roadmap', 'Architecture Review', 'Digital Strategy', 'Team Mentoring'],
            ],
        ];

        foreach ($map as $slug => $fields) {
            $this->apply(Service::where('slug', $slug)->first(), $fields);
        }
    }

    private function solutions(): void
    {
        $map = [
            'inventory-system' => [
                'title' => 'Inventory System',
                'short_description' => 'Real-time inventory management for stock tracking, warehousing, and suppliers.',
            ],
            'crm' => [
                'title' => 'CRM',
                'short_description' => 'Customer relationship management for leads, pipeline, and customer history.',
            ],
            'hr-system' => [
                'title' => 'HR System',
                'short_description' => 'HR system for employees, payroll, attendance, and personnel records.',
            ],
            'finance-system' => [
                'title' => 'Finance System',
                'short_description' => 'Accounting and finance system for invoicing, expense tracking, and reporting.',
            ],
            'erp' => [
                'title' => 'ERP',
                'short_description' => 'Enterprise resource planning that brings every operational function into one place.',
            ],
            'dashboard' => [
                'title' => 'Dashboard',
                'short_description' => 'Business intelligence dashboards for data visualisation and faster decisions.',
            ],
            'ai-customer-service' => [
                'title' => 'AI Customer Service',
                'short_description' => 'AI-powered customer service with chatbot, knowledge base, and auto-response.',
            ],
            'ai-agent' => [
                'title' => 'AI Agent',
                'short_description' => 'Autonomous AI agents for complex task automation and decision support.',
            ],
            'business-automation' => [
                'title' => 'Business Automation',
                'short_description' => 'Workflow automation that removes manual work and lifts day-to-day efficiency.',
            ],
            'custom-system' => [
                'title' => 'Custom System',
                'short_description' => 'A system designed specifically around your company\'s own processes.',
            ],
        ];

        foreach ($map as $slug => $fields) {
            $this->apply(Solution::where('slug', $slug)->first(), $fields);
        }
    }

    private function processSteps(): void
    {
        $map = [
            1 => ['title' => 'Consultation',      'description' => 'An in-depth conversation about business needs and the goals you want to reach.'],
            2 => ['title' => 'Business Analysis', 'description' => 'Business process analysis, pain points, and requirements gathering.'],
            3 => ['title' => 'System Design',     'description' => 'System architecture, database design, and UI/UX design.'],
            4 => ['title' => 'Development',       'description' => 'Development in an agile rhythm, with regular updates you can review.'],
            5 => ['title' => 'Testing',           'description' => 'Quality assurance, user acceptance testing, and performance testing.'],
            6 => ['title' => 'Deployment',        'description' => 'Deployment to the production server with a zero-downtime strategy.'],
            7 => ['title' => 'Support',           'description' => 'Ongoing support, maintenance, and iterative improvement.'],
        ];

        foreach ($map as $number => $fields) {
            $this->apply(ProcessStep::where('step_number', $number)->first(), $fields);
        }
    }

    private function portfolios(): void
    {
        $map = [
            'demo-inventory-management-system' => [
                'short_description' => 'Real-time inventory management with warehouse handling, stock tracking, and reporting.',
            ],
            'demo-ai-customer-service-chatbot' => [
                'short_description' => 'An AI-powered chatbot with a knowledge base for automated customer service.',
            ],
            'demo-business-automation-dashboard' => [
                'short_description' => 'A monitoring dashboard that automates business workflows with real-time analytics.',
            ],
        ];

        foreach ($map as $slug => $fields) {
            $this->apply(Portfolio::where('slug', $slug)->first(), $fields);
        }
    }

    private function faqs(): void
    {
        $map = [
            'Berapa lama waktu pembuatan sistem custom?' => [
                'question' => 'How long does a custom system take to build?',
                'answer' => 'It depends on the complexity. A standard MVP or quick system takes roughly 2 to 4 weeks. A mid-sized custom business system or ERP takes 4 to 10 weeks. A large multi-tenant enterprise or SaaS platform takes 3 to 6 months, released in milestones.',
                'category' => 'General',
            ],
            'Apakah bisa request perubahan setelah project selesai?' => [
                'question' => 'Can I request changes after the project is finished?',
                'answer' => 'Yes. Every project includes a post-launch warranty period for fixes, and further development can continue under a maintenance arrangement with a clear SLA.',
                'category' => 'General',
            ],
            'Teknologi apa yang digunakan?' => [
                'question' => 'Which technologies do you use?',
                'answer' => 'A modern, scalable enterprise stack: Laravel and PHP 8+, Python (FastAPI/Django) and Node.js on the backend; Vue.js 3, React, Next.js and Tailwind CSS on the frontend; PostgreSQL, MySQL and Redis for data; Flutter and React Native for mobile; plus OpenAI, LangChain and custom AI agents for automation.',
                'category' => 'Technical',
            ],
            'Bagaimana cara memulai project?' => [
                'question' => 'How do we get started?',
                'answer' => 'Start with a free consultation. We discuss your process and constraints, then send you a scope document with a roadmap and a transparent estimate — no hidden costs.',
                'category' => 'General',
            ],
            'Apakah bisa integrasi dengan sistem yang sudah ada?' => [
                'question' => 'Can it integrate with our existing systems?',
                'answer' => 'Yes. We integrate through REST APIs, webhooks, GraphQL, or direct database connections — including ERPs, payment gateways, the WhatsApp Business API, logistics providers, and hardware such as thermal POS printers.',
                'category' => 'Technical',
            ],
        ];

        foreach ($map as $question => $fields) {
            $this->apply(Faq::where('question', $question)->first(), $fields);
        }
    }

    private function ceoProfile(): void
    {
        $this->apply(CeoProfile::first(), [
            'position' => 'CEO & System/Application Developer',
            'short_bio' => 'Deni Afrizal is an IT professional who began as a developer and grew into an IT project manager — combining software engineering, system architecture, business process analysis, and project management.',
            'full_bio' => "Deni Afrizal is an IT professional who began his career as a developer and grew into an IT project manager. That path produced an unusual combination: software engineering, system architecture, business process analysis, and project management in one person.\n\nFocusing on custom software development, SaaS, AI, and business automation, Deni helps companies build digital systems that fit how they actually operate.\n\nHis aim is to support the digital transformation of Indonesian business through high-quality software designed on a genuine understanding of the underlying process.",
            'skills' => ['Software Development', 'System Architecture', 'SaaS Development', 'AI & Machine Learning', 'Business Automation', 'IT Project Management', 'Business Process Analysis'],
            'experience' => ['Software Developer', 'Full-stack Developer', 'IT Project Manager', 'CEO & System Architect — Aldef Tech'],
        ]);
    }

    private function siteSettings(): void
    {
        $map = [
            'site_tagline' => 'Build digital systems that move the business.',
            'about_title' => 'About Aldef Tech',
            'about_subtitle' => 'Aldef Tech is a premium digital technology partner helping companies build the digital systems their operations actually need.',
            'about_mission' => 'To help businesses build digital systems that are effective, scalable, and genuinely suited to their needs.',
            'about_vision' => 'To be the technology partner Indonesian businesses trust for their digital transformation.',
            'footer_description' => 'A corporate digital transformation partner — designing and building systems, applications, SaaS platforms, and AI-driven automation that accelerate business growth.',
            'seo_default_title' => 'Aldef Tech — Custom Systems, Applications, SaaS & AI',
            'seo_default_description' => 'Aldef Tech helps businesses build custom systems, applications, SaaS platforms, websites, AI, and automation. Talk it through with Deni Afrizal.',
        ];

        foreach ($map as $key => $value) {
            $this->apply(SiteSetting::where('key', $key)->first(), ['value' => $value]);
        }
    }
}
