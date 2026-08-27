<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index()
    {
        try {
            $portfolios = Portfolio::published()->ordered()->with('category')->get();
            $categories = PortfolioCategory::withCount('portfolios')->orderBy('sort_order')->get();
        } catch (\Throwable $e) {
            $portfolios = collect();
            $categories = collect();
        }

        if ($portfolios->isEmpty()) {
            $portfolios = collect([
                (object)[
                    'title' => 'Arahinn Mobile — OTA & Travel Platform',
                    'slug' => 'arahinn-mobile-ota',
                    'client' => 'PT Arahinn Digital Nusantara',
                    'category' => (object)['name' => 'Project OTA • Mobile Ecosystem', 'slug' => 'ota-travel'],
                    'featured_image' => 'images/portfolio/arahinn-mobile.webp',
                    'short_description' => 'Aplikasi mobile Online Travel Agent modern dengan integrasi real-time inventory kamar, engine pencarian instan, payment gateway multi-channel otomatis, dan sistem loyalty rewards terpadu.',
                    'technologies' => ['Laravel API', 'Flutter / Mobile', 'PostgreSQL', 'Midtrans Gateway', 'Redis Cache'],
                ],
                (object)[
                    'title' => 'Bamboe Oerip — Booking Engine & Hospitality OTA',
                    'slug' => 'bamboe-oerip-booking-engine',
                    'client' => 'Bamboe Oerip Hospitality Group',
                    'category' => (object)['name' => 'Project OTA • Hospitality Management', 'slug' => 'hospitality-booking'],
                    'featured_image' => 'images/portfolio/bamboe-oerip.webp',
                    'short_description' => 'Sistem reservasi dan manajemen hospitality digital berbasis web dengan dynamic pricing engine, kalender okupansi interaktif, automated WhatsApp billing invoice, dan integrasi channel manager.',
                    'technologies' => ['Laravel 11', 'Vue.js 3', 'Tailwind CSS', 'MySQL', 'WhatsApp Business API'],
                ],
                (object)[
                    'title' => 'Aldef POS — Omnichannel Smart POS System',
                    'slug' => 'aldef-pos-smart-system',
                    'client' => 'Aldef Enterprise Retail',
                    'category' => (object)['name' => 'Project POS Sistem • Multi-Outlet', 'slug' => 'pos-retail'],
                    'featured_image' => 'images/portfolio/aldeftech-pos.webp',
                    'short_description' => 'Platform Point of Sale (POS) cloud omnichannel berkecepatan tinggi dengan sinkronisasi inventori multi-cabang, barcode scanning offline-ready, audit kasir real-time, dan analitik performa laba-rugi.',
                    'technologies' => ['Laravel', 'Electron / PWA', 'PostgreSQL', 'Thermal Printing', 'WebSockets'],
                ],
                (object)[
                    'title' => 'Smart Attendance — Biometric & Geofencing HRIS',
                    'slug' => 'smart-attendance-biometric-face-recognition',
                    'client' => 'Enterprise Workforce Management',
                    'category' => (object)['name' => 'Project Enterprise HR • Biometric Mobile', 'slug' => 'hris-attendance'],
                    'featured_image' => 'images/portfolio/absensi.webp',
                    'short_description' => 'Sistem presensi karyawan cerdas berbasis AI Face Recognition biometrik dan validasi radius GPS (lock location) anti-fake GPS, terintegrasi otomatis dengan payroll dan manajemen shift multi-cabang.',
                    'technologies' => ['Laravel 11 API', 'Flutter Mobile', 'AI Face Recognition', 'PostGIS Geofencing', 'PostgreSQL'],
                ],
                (object)[
                    'title' => 'Aldef Cloud Drive — Multi-Tenant Enterprise Storage',
                    'slug' => 'aldef-cloud-drive-storage',
                    'client' => 'Multi-Enterprise Storage Solution',
                    'category' => (object)['name' => 'Project Cloud Storage • Multi-Tenant SaaS', 'slug' => 'cloud-drive'],
                    'featured_image' => 'images/portfolio/drive.webp',
                    'short_description' => 'Platform cloud storage multi-perusahaan (multi-tenant) berkecepatan tinggi dengan antarmuka modern drag-and-drop, enkripsi berkas end-to-end, kolaborasi izin akses folder, dan audit log keamanan terpusat.',
                    'technologies' => ['Laravel 11', 'Vue.js 3', 'S3 Object Storage', 'Multi-Tenant SaaS', 'Tailwind CSS'],
                ],
                (object)[
                    'title' => 'MotoRide Connect — Touring & Telemetry Ecosystem',
                    'slug' => 'motoride-connect-touring-telemetry',
                    'client' => 'Komunitas Motor & Rider Federation',
                    'category' => (object)['name' => 'Project Mobile Telemetry • Real-Time IoT', 'slug' => 'touring-telemetry'],
                    'featured_image' => 'images/portfolio/touring.webp',
                    'short_description' => 'Ekosistem aplikasi mobile komunitas touring motor dengan pelacakan posisi konvoi real-time (live tracking), sinyal darurat SOS instan saat kendala mesin/kecelakaan, serta telemetri speedometer digital dan rute navigasi terintegrasi.',
                    'technologies' => ['Flutter / Mobile', 'WebSockets Realtime', 'OpenStreetMap Telemetry', 'SOS Emergency Engine', 'Redis'],
                ],
            ]);
        }

        return view('pages.portfolio', compact('portfolios', 'categories'));
    }

    public function show(Portfolio $portfolio)
    {
        if (!$portfolio->is_published) {
            abort(404);
        }

        $portfolio->load(['category', 'images']);
        $relatedPortfolios = Portfolio::published()
            ->where('category_id', $portfolio->category_id)
            ->where('id', '!=', $portfolio->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('pages.portfolio-show', ['portfolio' => $portfolio, 'relatedPortfolios' => $relatedPortfolios]);
    }
}
