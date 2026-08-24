<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Aldef Tech Configuration
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Aldef Tech'),
    'tagline' => 'Bangun Sistem Digital yang Menggerakkan Bisnis.',
    'tagline_en' => 'Technology That Moves Your Business Forward.',
    'positioning' => 'Premium Digital Technology Partner',

    'whatsapp' => [
        'number' => env('WHATSAPP_NUMBER', ''),
        'default_message' => env('WHATSAPP_DEFAULT_MESSAGE', 'Hallo Aldef Tech, saya ingin berkonsultasi mengenai kebutuhan sistem/aplikasi.'),
    ],

    'analytics' => [
        'google_analytics_id' => env('GOOGLE_ANALYTICS_ID', ''),
        'google_tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', ''),
        'meta_pixel_id' => env('META_PIXEL_ID', ''),
        'google_search_console_verification' => env('GOOGLE_SEARCH_CONSOLE_VERIFICATION', ''),
    ],

    'seo' => [
        'default_title' => 'Aldef Tech — Jasa Pembuatan Sistem, Aplikasi, SaaS & AI',
        'default_description' => 'Aldef Tech membantu bisnis membangun sistem, aplikasi custom, SaaS, website, AI, dan automasi bisnis sesuai kebutuhan. Konsultasi bersama Deni Afrizal.',
        'default_image' => '/images/og-default.jpg',
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@aldeftech.com'),
        'name' => env('ADMIN_NAME', 'Admin Aldef Tech'),
        'password' => env('ADMIN_PASSWORD', 'password'),
    ],

    'upload' => [
        'max_size' => 5120, // KB
        'allowed_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'],
        'image_max_width' => 2400,
        'image_max_height' => 2400,
    ],

    'lead' => [
        'project_types' => [
            'Website',
            'Custom System',
            'Web Application',
            'SaaS',
            'AI',
            'Automation',
            'Mobile App',
            'API Integration',
            'Other',
        ],
        'budget_ranges' => [
            '< Rp10 juta',
            'Rp10–25 juta',
            'Rp25–50 juta',
            'Rp50–100 juta',
            '> Rp100 juta',
            'Belum menentukan',
        ],
        'statuses' => [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal' => 'Proposal',
            'negotiation' => 'Negotiation',
            'won' => 'Won',
            'lost' => 'Lost',
        ],
        'sources' => [
            'website' => 'Website',
            'whatsapp' => 'WhatsApp',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'google' => 'Google',
            'referral' => 'Referral',
            'other' => 'Other',
        ],
    ],

    'portfolio' => [
        'categories' => [
            'Web Application',
            'Business System',
            'SaaS',
            'AI',
            'Automation',
            'Website',
            'Mobile App',
            'API Integration',
        ],
    ],

    'blog' => [
        'categories' => [
            'AI',
            'SaaS',
            'Software Development',
            'Digital Transformation',
            'Automation',
            'Business',
            'IT',
            'Technology',
        ],
    ],
];
