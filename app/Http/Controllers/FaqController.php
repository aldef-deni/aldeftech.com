<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        try {
            $faqs = Faq::published()->ordered()->get();
        } catch (\Throwable $e) {
            $faqs = collect();
        }

        // If no FAQs in DB yet or few, provide fallback curated items
        if ($faqs->isEmpty()) {
            $faqs = collect([
                (object)[
                    'id' => 1,
                    'question' => 'Bagaimana proses konsultasi awal dan estimasi biaya di Aldef Tech?',
                    'answer' => 'Kami memulai dengan sesi discovery meeting 1-on-1 (bisa online via Zoom/GMeet atau tatap muka). Kami menggali model proses bisnis, arsitektur data, pain point utama, dan ekspektasi fitur Anda. Setelah itu, kami merilis proposal dokumen scope of work komprehensif beserta roadmap milestone dan estimasi investasi transparan tanpa biaya tersembunyi.',
                    'category' => 'General',
                ],
                (object)[
                    'id' => 2,
                    'question' => 'Berapa rata-rata durasi pembuatan software custom atau aplikasi web?',
                    'answer' => 'Durasi pengerjaan sangat bergantung pada kompleksitas modul dan integrasi. Untuk sistem MVP / Quick System standar berkisar antara 2 hingga 4 minggu. Untuk Custom Business System / ERP skala menengah berkisar antara 4 hingga 10 minggu. Sedangkan platform Enterprise & SaaS multi-tenant berskala besar berkisar antara 3 hingga 6 bulan dengan rilis berkala per milestone.',
                    'category' => 'General',
                ],
                (object)[
                    'id' => 3,
                    'question' => 'Tech stack apa saja yang menjadi keunggulan Aldef Tech?',
                    'answer' => 'Kami mengadopsi stack enterprise modern yang scalable, secure, dan berkinerja tinggi: Backend (Laravel, PHP 8+, Python FastAPI/Django, Node.js), Frontend (Vue.js 3, React, Next.js, Tailwind CSS), Database & Cache (PostgreSQL, MySQL, Redis), Mobile & Desktop (Flutter, React Native, Electron), serta integrasi AI / Automation (OpenAI API, LangChain, Custom AI Agents, WebSockets).',
                    'category' => 'Technical',
                ],
                (object)[
                    'id' => 4,
                    'question' => 'Apakah sistem baru bisa diintegrasikan dengan database atau software yang sudah berjalan (Legacy System)?',
                    'answer' => 'Tentu saja. Kami memiliki pengalaman mendalam dalam integrasi sistem melalui REST API, Webhooks, GraphQL, maupun koneksi database langsung. Kami dapat menghubungkan sistem Anda ke ERP existing, Payment Gateway (Midtrans, Xendit), WhatsApp Business API, Kurir Logistik, CRM, maupun perangkat keras seperti printer POS barcode thermal.',
                    'category' => 'Technical',
                ],
                (object)[
                    'id' => 5,
                    'question' => 'Bagaimana dengan hak kepemilikan source code dan kekayaan intelektual (IP)?',
                    'answer' => '100% Full Code & Intellectual Property Ownership menjadi milik klien sepenuhnya setelah project diserahterimakan dan dilunasi. Anda mendapatkan akses repositori penuh (GitHub/GitLab), dokumentasi arsitektur, skema database, dan panduan deployment tanpa royalti berulang (No Vendor Lock-in).',
                    'category' => 'Security & IP',
                ],
                (object)[
                    'id' => 6,
                    'question' => 'Bagaimana jaminan keamanan data dan kerahasiaan bisnis perusahaan kami?',
                    'answer' => 'Kami menerapkan standar keamanan enterprise: penandatanganan NDA (Non-Disclosure Agreement) sebelum project dimulai, enkripsi data saat transit (SSL/TLS) dan rest (AES-256), proteksi OWASP Top 10 (CSRF, XSS, SQL Injection), isolasi environment staging vs production, serta audit berkala.',
                    'category' => 'Security & IP',
                ],
                (object)[
                    'id' => 7,
                    'question' => 'Apa saja layanan purna jual (Post-Launch SLA & Support) yang disediakan?',
                    'answer' => 'Setiap project kami lindungi dengan masa garansi bebas bug (warranty period). Kami juga menyediakan paket Maintenance SLA (Service Level Agreement) berkelanjutan yang mencakup pemantauan uptime server 24/7, pencadangan database rutin (automated backup), update patch keamanan, serta respons cepat dari tim engineer kami.',
                    'category' => 'SLA & Support',
                ],
                (object)[
                    'id' => 8,
                    'question' => 'Bagaimana skema pembayaran dan termin project di Aldef Tech?',
                    'answer' => 'Skema pembayaran kami terstruktur berdasarkan pencapaian milestone transparan (misalnya: DP 30% saat Kickoff, 40% setelah verifikasi staging/UAT, dan 30% setelah Final Deployment ke Production Server). Kami juga melayani kontrak retainer bulanan untuk pengembangan fitur berkesinambungan.',
                    'category' => 'SLA & Support',
                ],
            ]);
        }

        $categories = $faqs->pluck('category')->filter()->unique()->values();

        return view('pages.faq', compact('faqs', 'categories'));
    }
}
