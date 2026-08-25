<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            $services = Service::published()->ordered()->get();
        } catch (\Throwable $e) {
            $services = collect();
        }

        if ($services->isEmpty()) {
            $services = collect([
                (object)[
                    'title' => 'Custom Software Engineering',
                    'slug' => 'custom-software-engineering',
                    'icon' => '⚡',
                    'short_description' => 'Pengembangan software bisnis kustom yang dibangun dari nol sesuai alur kerja, hierarki data, dan kebutuhan operasional unik perusahaan Anda.',
                    'description' => 'Kami merancang arsitektur backend andal, database ternormalisasi, dan antarmuka web modern yang scalable. Solusi kami mengeliminasi keterbatasan software off-the-shelf dan memberikan Anda kendali 100% atas logika bisnis tanpa biaya lisensi berkala.',
                    'features' => [
                        'Clean Modular Architecture (Laravel / Node / Python)',
                        'Robust REST & GraphQL API Engine',
                        'Relational & High-Speed Cache (PostgreSQL / Redis)',
                        'Role-Based Access Control (RBAC) & Audit Logs',
                        '100% Full Source Code & IP Ownership',
                        'Comprehensive Technical & API Documentation',
                    ],
                ],
                (object)[
                    'title' => 'SaaS Platform Development',
                    'slug' => 'saas-platform-development',
                    'icon' => '☁️',
                    'short_description' => 'Arsitektur platform Software-as-a-Service multi-tenant yang scalable, aman, siap monetisasi, dan mampu menangani ribuan tenant secara terisolasi.',
                    'description' => 'Mulai dari MVP hingga platform enterprise global, kami menangani database tenant isolation, automated subscription billing, metering usage, webhook triggers, dan automated onboarding funnel untuk mengakselerasi go-to-market produk Anda.',
                    'features' => [
                        'Multi-Tenant Database Isolation Architecture',
                        'Automated Recurring Billing & Payment Gateways',
                        'Subscription Tier Management & Usage Quotas',
                        'Automated Customer Onboarding & Provisioning',
                        'High Availability & Auto-Scaling Cloud Setup',
                        'Admin Super Dashboard & Revenue Analytics',
                    ],
                ],
                (object)[
                    'title' => 'AI Solutions & Intelligent Automation',
                    'slug' => 'ai-solutions-automation',
                    'icon' => '🧠',
                    'short_description' => 'Integrasi kecerdasan buatan, LLM agents, otomasi proses cerdas, dan predictive analytics untuk melipatgandakan efisiensi operasional tim.',
                    'description' => 'Transformasikan dokumen manual, customer support, dan ekstraksi data menjadi workflow otomatis bertenaga AI. Kami mengintegrasikan Large Language Model, RAG (Retrieval-Augmented Generation), dan computer vision ke dalam proses bisnis inti perusahaan.',
                    'features' => [
                        'Custom AI Autonomous Agents & Assistant Integration',
                        'Intelligent Document Processing (OCR & Data Extraction)',
                        'RAG Knowledge Base & Enterprise Semantic Search',
                        'Automated Multi-Channel WhatsApp Business Workflows',
                        'Predictive Analytics & Anomaly Detection Models',
                        'Secure On-Premise / Cloud AI Gateway Compliance',
                    ],
                ],
                (object)[
                    'title' => 'Business Process Automation',
                    'slug' => 'business-process-automation',
                    'icon' => '⚙️',
                    'short_description' => 'Digitalisasi dan automasi alur kerja manual end-to-end antar departemen untuk memangkas human error dan waktu operasional.',
                    'description' => 'Hilangkan bottleneck pada approval berjenjang, input data berulang antar software, serta pelaporan manual. Kami membangun sistem automasi berbasis event dan webhook yang menghubungkan seluruh software internal Anda secara real-time.',
                    'features' => [
                        'End-to-End Workflow Engine & Approval Chains',
                        'Automated Report Generation (PDF / Excel / WhatsApp)',
                        'Real-Time Webhook & Event-Driven Triggers',
                        'Cross-Department Task Routing & Alerts',
                        'Legacy Data Migration & Sync Middleware',
                        'Operational SLA Tracking & Real-Time Monitoring',
                    ],
                ],
                (object)[
                    'title' => 'System Integration & Enterprise API',
                    'slug' => 'system-integration-api',
                    'icon' => '🔗',
                    'short_description' => 'Menghubungkan berbagai sistem terpisah, ERP existing, payment gateway, database legacy, dan platform pihak ketiga secara seamless.',
                    'description' => 'Ciptakan single source of truth untuk data bisnis Anda. Kami membangun middleware dan API gateway berkinerja tinggi dengan keamanan enkripsi ketat, rate limiting, error tracking, dan auto-retry mechanism.',
                    'features' => [
                        'Payment Gateway Integration (Midtrans / Xendit / DOKU)',
                        'Official WhatsApp & Omnichannel Chat API',
                        'ERP & Accounting Sync (SAP / Odoo / Jurnal)',
                        'Logistics & Courier Shipping Rates Engine',
                        'Hardware Integration (POS Thermal, Barcode, RFID)',
                        'Real-Time WebSockets Sync & Event Pipelines',
                    ],
                ],
                (object)[
                    'title' => 'Web App Modernization & Performance Tuning',
                    'slug' => 'app-modernization-performance',
                    'icon' => '🚀',
                    'short_description' => 'Refactoring kode legacy, migrasi ke arsitektur modern, audit keamanan sistem, dan optimasi performa tinggi dengan response time kilat.',
                    'description' => 'Jika aplikasi atau database Anda mulai lambat, sering crash saat traffic tinggi, atau sulit dikembangkan, tim engineer kami melakukan audit mendalam, query optimization, caching layer, dan refactoring modular untuk performa maksimal.',
                    'features' => [
                        'Deep Query Optimization & Database Indexing',
                        'Redis In-Memory Caching & Load Balancing',
                        'Legacy PHP / Framework Modernization',
                        'Frontend Speed Optimization & PWA Conversion',
                        'OWASP Top 10 Security Audit & Penetration Hardening',
                        'Zero-Downtime Deployment & CI/CD Pipeline',
                    ],
                ],
            ]);
        }

        return view('pages.services', ['services' => $services]);
    }
}
