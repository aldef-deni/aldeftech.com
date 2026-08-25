<?php

namespace App\Http\Controllers;

use App\Models\Solution;

class SolutionController extends Controller
{
    public function index()
    {
        try {
            $solutions = Solution::published()->ordered()->get();
        } catch (\Throwable $e) {
            $solutions = collect();
        }

        if ($solutions->isEmpty()) {
            $solutions = collect([
                (object)[
                    'title' => 'ERP & Business Core System',
                    'slug' => 'erp-business-core',
                    'icon' => '🏢',
                    'short_description' => 'Sistem inti enterprise terpusat untuk mengintegrasikan finance, human resource, operasional gudang, procurement, dan pelaporan eksekutif.',
                    'features' => [
                        'Multi-Company & Multi-Branch Structure',
                        'Real-Time Balance Sheet & Financial Reporting',
                        'Automated Purchase Orders & Approval Hierarchies',
                        'Comprehensive Audit Trail & User Permissions',
                    ],
                ],
                (object)[
                    'title' => 'Omnichannel Smart POS System',
                    'slug' => 'omnichannel-pos-system',
                    'icon' => '💳',
                    'short_description' => 'Platform kasir pintar multi-outlet berkecepatan tinggi dengan sinkronisasi offline-to-online, barcode scanner, dan integrasi QRIS dinamis.',
                    'features' => [
                        'Offline-Ready Transaction Engine',
                        'Multi-Outlet Inventory Synchronization',
                        'Integrated Thermal Printing & Digital Receipts',
                        'Daily Cashier Reconciliation & Shift Reports',
                    ],
                ],
                (object)[
                    'title' => 'Multi-Channel CRM & Sales Funnel',
                    'slug' => 'crm-sales-funnel',
                    'icon' => '🎯',
                    'short_description' => 'Platform manajemen leads, automasi broadcast WhatsApp, pipeline follow-up tim sales, dan analitik konversi penjualan.',
                    'features' => [
                        'Visual Kanban Sales Pipeline Tracking',
                        'Official WhatsApp Cloud API Multi-Agent Inbox',
                        'Automated Follow-Up Sequences & Reminders',
                        'Lead Source ROI & Performance Analytics',
                    ],
                ],
                (object)[
                    'title' => 'Supply Chain, Warehouse & Logistics',
                    'slug' => 'supply-chain-logistics',
                    'icon' => '📦',
                    'short_description' => 'Sistem manajemen rantai pasok dan pergudangan modern dengan multi-warehouse transfer, batch tracking, dan barcode picking.',
                    'features' => [
                        'Multi-Warehouse Stock Reallocation',
                        'Batch, Expire Date & Serial Number Tracking',
                        'Automated Minimum Stock Re-Order Alerts',
                        'Logistics Courier Shipping & Waybill Sync',
                    ],
                ],
                (object)[
                    'title' => 'Smart Hospitality & Booking Engine',
                    'slug' => 'hospitality-booking-engine',
                    'icon' => '🏨',
                    'short_description' => 'Sistem reservasi dan operasional hotel/villa berbasis web dengan dynamic pricing engine, kalender okupansi, dan auto-invoice.',
                    'features' => [
                        'Interactive Room Availability Matrix',
                        'Dynamic Pricing by Season & Weekend Rates',
                        'Automated WhatsApp Booking Confirmation',
                        'Integrated Payment Gateway Settlement',
                    ],
                ],
                (object)[
                    'title' => 'Fintech, Billing & Payment Gateway',
                    'slug' => 'fintech-billing-payment',
                    'icon' => '💸',
                    'short_description' => 'Infrastruktur billing otomatis, invoice generator, auto-disbursement, dan integrasi payment gateway berstandar enkripsi perbankan.',
                    'features' => [
                        'Recurring Subscription & Split Billing',
                        'Auto-Reconciliation via Midtrans / Xendit / Bank',
                        'Automated Tax (PPN/PPh) Invoice Generation',
                        'Bank-Grade 256-Bit Data Encryption',
                    ],
                ],
            ]);
        }

        return view('pages.solutions', [
            'solutions' => $solutions,
            'pageTitle' => 'Solusi Software Enterprise & AI — Aldef Tech',
            'metaDescription' => 'Solusi software enterprise untuk ERP, CRM, POS, inventory, warehouse, finance, HR, dashboard, AI customer service, AI Agent, dan business automation.',
            'canonical' => route('solutions'),
        ]);
    }
}
