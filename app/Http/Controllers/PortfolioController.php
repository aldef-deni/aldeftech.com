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
                    'bg_class' => 'bg-gradient-to-b from-[#F0F7FF] to-[#E6F1FD] border-[#BFDBFE]/80 shadow-[0_14px_34px_-8px_rgba(37,99,235,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(37,99,235,0.28)] hover:border-blue-400',
                    'pill_class' => 'text-blue-700 bg-blue-100/90 border border-blue-200',
                    'accent_hover' => 'group-hover:text-blue-600',
                    'btn_class' => 'text-blue-600 hover:text-blue-800'
                ],
                (object)[
                    'title' => 'Bamboe Oerip — Booking Engine & Hospitality OTA',
                    'slug' => 'bamboe-oerip-booking-engine',
                    'client' => 'Bamboe Oerip Hospitality Group',
                    'category' => (object)['name' => 'Project OTA • Hospitality Management', 'slug' => 'hospitality-booking'],
                    'featured_image' => 'images/portfolio/bamboe-oerip.webp',
                    'short_description' => 'Sistem reservasi dan manajemen hospitality digital berbasis web dengan dynamic pricing engine, kalender okupansi interaktif, automated WhatsApp billing invoice, dan integrasi channel manager.',
                    'technologies' => ['Laravel 11', 'Vue.js 3', 'Tailwind CSS', 'MySQL', 'WhatsApp Business API'],
                    'bg_class' => 'bg-gradient-to-b from-[#F0FDF4] to-[#DCFCE7] border-[#BBF7D0]/80 shadow-[0_14px_34px_-8px_rgba(16,185,129,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(16,185,129,0.28)] hover:border-emerald-400',
                    'pill_class' => 'text-emerald-800 bg-emerald-100/90 border border-emerald-200',
                    'accent_hover' => 'group-hover:text-emerald-600',
                    'btn_class' => 'text-emerald-700 hover:text-emerald-900'
                ],
                (object)[
                    'title' => 'Aldef POS — Omnichannel Smart POS System',
                    'slug' => 'aldef-pos-smart-system',
                    'client' => 'Aldef Enterprise Retail',
                    'category' => (object)['name' => 'Project POS Sistem • Multi-Outlet', 'slug' => 'pos-retail'],
                    'featured_image' => 'images/portfolio/aldeftech-pos.webp',
                    'short_description' => 'Platform Point of Sale (POS) cloud omnichannel berkecepatan tinggi dengan sinkronisasi inventori multi-cabang, barcode scanning offline-ready, audit kasir real-time, dan analitik performa laba-rugi.',
                    'technologies' => ['Laravel', 'Electron / PWA', 'PostgreSQL', 'Thermal Printing', 'WebSockets'],
                    'bg_class' => 'bg-gradient-to-b from-[#F5F3FF] to-[#ECE7FD] border-[#DDD6FE]/80 shadow-[0_14px_34px_-8px_rgba(99,102,241,0.15)] hover:shadow-[0_24px_48px_-10px_rgba(99,102,241,0.28)] hover:border-indigo-400',
                    'pill_class' => 'text-indigo-700 bg-indigo-100/90 border border-indigo-200',
                    'accent_hover' => 'group-hover:text-indigo-600',
                    'btn_class' => 'text-indigo-600 hover:text-indigo-800'
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
