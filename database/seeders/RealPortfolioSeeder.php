<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use Illuminate\Database\Seeder;

/**
 * The six real case studies, under the slugs Google already knows.
 *
 * These are the targets of PortfolioController::LEGACY_SLUGS, so seeding them
 * also brings the old placeholder addresses back to a 301 instead of a 404.
 * Matched on slug, so running it again refreshes the rows instead of
 * duplicating them. The three "Demo — …" records from DatabaseSeeder are
 * soft-deleted: they would otherwise sit in the listing beside the real work.
 *
 *   php artisan db:seed --class=RealPortfolioSeeder --force
 */
class RealPortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = PortfolioCategory::pluck('id', 'slug');

        foreach ($this->portfolios() as $index => $item) {
            $categorySlug = $item['category'];
            $english = $item['en'];
            unset($item['category'], $item['en']);

            $portfolio = Portfolio::withTrashed()->firstOrNew(['slug' => $item['slug']]);
            $portfolio->fill($item + [
                'category_id' => $categories[$categorySlug] ?? null,
                'is_featured' => $index < 3,
                'is_published' => true,
                'published_at' => $portfolio->published_at ?? now(),
                'sort_order' => $index + 1,
            ]);
            $portfolio->setTranslations('en', $english);
            $portfolio->deleted_at = null;
            $portfolio->save();
        }

        Portfolio::where('slug', 'like', 'demo-%')->delete();

        $this->command?->info('Real portfolios seeded: ' . count($this->portfolios()));
    }

    private function portfolios(): array
    {
        return [
            [
                'slug' => 'arahinn-superapps',
                'title' => 'ArahInn SuperApps — OTA & Travel Platform',
                'client' => 'PT Arahinn Digital Nusantara',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/arahinn-mobile.webp',
                'short_description' => 'Aplikasi mobile Online Travel Agent modern dengan integrasi real-time inventory kamar, engine pencarian instan, payment gateway multi-channel otomatis, dan sistem loyalty rewards terpadu.',
                'technologies' => ['Laravel API', 'Flutter / Mobile', 'PostgreSQL', 'Midtrans Gateway', 'Redis Cache'],
                'en' => [
                    'title' => 'ArahInn SuperApps — OTA & Travel Platform',
                    'short_description' => 'A modern Online Travel Agent mobile app with real-time room inventory, instant search, automated multi-channel payments, and an integrated loyalty rewards programme.',
                ],
            ],
            [
                'slug' => 'bamboe-oerip-villabox',
                'title' => 'Bamboe Oerip VillaBox — Booking Engine & Hospitality OTA',
                'client' => 'Bamboe Oerip Hospitality Group',
                'category' => 'web-application',
                'featured_image' => 'images/portfolio/bamboe-oerip.webp',
                'short_description' => 'Sistem reservasi dan manajemen hospitality digital berbasis web dengan dynamic pricing engine, kalender okupansi interaktif, automated WhatsApp billing invoice, dan integrasi channel manager.',
                'technologies' => ['Laravel 11', 'Vue.js 3', 'Tailwind CSS', 'MySQL', 'WhatsApp Business API'],
                'en' => [
                    'title' => 'Bamboe Oerip VillaBox — Booking Engine & Hospitality OTA',
                    'short_description' => 'A web-based reservation and hospitality management system with dynamic pricing, an interactive occupancy calendar, automated WhatsApp invoicing, and channel manager integration.',
                ],
            ],
            [
                'slug' => 'sistem-pos-multi-cabang-aldef-tech',
                'title' => 'Sistem POS Multi-Cabang Aldef Tech',
                'client' => 'Aldef Enterprise Retail',
                'category' => 'business-system',
                'featured_image' => 'images/portfolio/aldeftech-pos.webp',
                'short_description' => 'Platform Point of Sale (POS) cloud omnichannel berkecepatan tinggi dengan sinkronisasi inventori multi-cabang, barcode scanning offline-ready, audit kasir real-time, dan analitik performa laba-rugi.',
                'technologies' => ['Laravel', 'Electron / PWA', 'PostgreSQL', 'Thermal Printing', 'WebSockets'],
                'en' => [
                    'title' => 'Aldef Tech Multi-Branch POS System',
                    'short_description' => 'A fast omnichannel cloud Point of Sale platform with multi-branch inventory sync, offline-ready barcode scanning, real-time cashier audits, and profit-and-loss analytics.',
                ],
            ],
            [
                'slug' => 'absensi-aldef-tech',
                'title' => 'Absensi Aldef Tech — Biometric & Geofencing HRIS',
                'client' => 'Enterprise Workforce Management',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/absensi.webp',
                'short_description' => 'Sistem presensi karyawan cerdas berbasis AI Face Recognition biometrik dan validasi radius GPS (lock location) anti-fake GPS, terintegrasi otomatis dengan payroll dan manajemen shift multi-cabang.',
                'technologies' => ['Laravel 11 API', 'Flutter Mobile', 'AI Face Recognition', 'PostGIS Geofencing', 'PostgreSQL'],
                'en' => [
                    'title' => 'Aldef Tech Attendance — Biometric & Geofencing HRIS',
                    'short_description' => 'Smart employee attendance using biometric AI face recognition and GPS radius lock with fake-GPS detection, wired straight into payroll and multi-branch shift management.',
                ],
            ],
            [
                'slug' => 'aplikasi-penyimpanan-drive-aldef-tech',
                'title' => 'Aldef Drive — Aplikasi Penyimpanan Multi-Tenant',
                'client' => 'Multi-Enterprise Storage Solution',
                'category' => 'saas',
                'featured_image' => 'images/portfolio/drive.webp',
                'short_description' => 'Platform cloud storage multi-perusahaan (multi-tenant) berkecepatan tinggi dengan antarmuka modern drag-and-drop, enkripsi berkas end-to-end, kolaborasi izin akses folder, dan audit log keamanan terpusat.',
                'technologies' => ['Laravel 11', 'Vue.js 3', 'S3 Object Storage', 'Multi-Tenant SaaS', 'Tailwind CSS'],
                'en' => [
                    'title' => 'Aldef Drive — Multi-Tenant Storage App',
                    'short_description' => 'A fast multi-tenant cloud storage platform with a modern drag-and-drop interface, end-to-end file encryption, shared folder permissions, and a central security audit log.',
                ],
            ],
            [
                'slug' => 'touring-aldef-tech',
                'title' => 'Touring Aldef Tech — Touring & Telemetry Ecosystem',
                'client' => 'Komunitas Motor & Rider Federation',
                'category' => 'mobile-app',
                'featured_image' => 'images/portfolio/touring.webp',
                'short_description' => 'Ekosistem aplikasi mobile komunitas touring motor dengan pelacakan posisi konvoi real-time (live tracking), sinyal darurat SOS instan saat kendala mesin/kecelakaan, serta telemetri speedometer digital dan rute navigasi terintegrasi.',
                'technologies' => ['Flutter / Mobile', 'WebSockets Realtime', 'OpenStreetMap Telemetry', 'SOS Emergency Engine', 'Redis'],
                'en' => [
                    'title' => 'Aldef Tech Touring — Touring & Telemetry Ecosystem',
                    'short_description' => 'A mobile ecosystem for motorcycle touring communities with live convoy tracking, instant SOS alerts for breakdowns or accidents, a digital speedometer, and integrated route navigation.',
                ],
            ],
        ];
    }
}
