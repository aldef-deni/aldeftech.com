<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\SiteSetting;
use App\Models\Service;
use App\Models\Solution;
use App\Models\PortfolioCategory;
use App\Models\Portfolio;
use App\Models\ProcessStep;
use App\Models\Faq;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\CeoProfile;
use App\Models\HomepageSection;
use App\Models\NavigationMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedAdmin();
        $this->seedSiteSettings();
        $this->seedServices();
        $this->seedSolutions();
        $this->seedPortfolioCategories();
        $this->seedPortfolios();
        $this->seedProcessSteps();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedBlogCategories();
        $this->seedBlogTags();
        $this->seedBlogPosts();
        $this->seedCeoProfile();
        $this->seedHomepageSections();
        $this->seedNavigation();
    }

    private function seedRolesAndPermissions(): void
    {
        $roles = [
            ['name' => 'super-admin', 'display_name' => 'Super Admin', 'description' => 'Full access to all features'],
            ['name' => 'editor', 'display_name' => 'Editor', 'description' => 'Can manage content'],
            ['name' => 'sales-manager', 'display_name' => 'Sales Manager', 'description' => 'Can manage leads'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['name' => 'manage-content', 'display_name' => 'Manage Content'],
            ['name' => 'manage-leads', 'display_name' => 'Manage Leads'],
            ['name' => 'manage-users', 'display_name' => 'Manage Users'],
            ['name' => 'manage-settings', 'display_name' => 'Manage Settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign all permissions to super-admin
        $superAdmin = Role::where('name', 'super-admin')->first();
        $superAdmin->permissions()->attach(Permission::all());

        // Assign content permissions to editor
        $editor = Role::where('name', 'editor')->first();
        $editor->permissions()->attach(Permission::whereIn('name', ['manage-content'])->get());

        // Assign lead permissions to sales-manager
        $salesManager = Role::where('name', 'sales-manager')->first();
        $salesManager->permissions()->attach(Permission::whereIn('name', ['manage-leads'])->get());
    }

    private function seedAdmin(): void
    {
        $admin = User::firstOrCreate(
            ['email' => config('aldeftech.admin.email', 'aldeftech@gmail.com')],
            [
                'name' => config('aldeftech.admin.name', 'Admin Aldef Tech'),
                'password' => Hash::make(config('aldeftech.admin.password', 'Alkamora1982')),
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole && !$admin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $admin->roles()->attach($superAdminRole->id);
        }
    }

    private function seedSiteSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Aldef Tech', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Bangun Sistem Digital yang Menggerakkan Bisnis.', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => 'images/logo.png', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => 'images/logo-square.png', 'type' => 'text', 'group' => 'general'],
            ['key' => 'email', 'value' => 'info@aldeftech.com', 'type' => 'text', 'group' => 'general'],
            ['key' => 'phone', 'value' => '+62 812-3456-7890', 'type' => 'text', 'group' => 'general'],
            ['key' => 'whatsapp_number', 'value' => '628128968609', 'type' => 'text', 'group' => 'general'],
            ['key' => 'address', 'value' => 'Indonesia', 'type' => 'text', 'group' => 'general'],
            ['key' => 'copyright', 'value' => '© ' . date('Y') . ' Aldef Tech. All rights reserved.', 'type' => 'text', 'group' => 'general'],
            ['key' => 'seo_default_title', 'value' => 'Aldef Tech — Jasa Pembuatan Sistem, Aplikasi, SaaS & AI', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'seo_default_description', 'value' => 'Aldef Tech membantu bisnis membangun sistem, aplikasi custom, SaaS, website, AI, dan automasi bisnis sesuai kebutuhan.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'about_title', 'value' => 'About Aldef Tech', 'type' => 'text', 'group' => 'about'],
            ['key' => 'about_subtitle', 'value' => 'Aldef Tech adalah premium digital technology partner yang membantu bisnis membangun sistem digital sesuai kebutuhan.', 'type' => 'textarea', 'group' => 'about'],
            ['key' => 'about_mission', 'value' => 'Membantu bisnis membangun sistem digital yang efektif, scalable, dan sesuai dengan kebutuhan operasional mereka.', 'type' => 'textarea', 'group' => 'about'],
            ['key' => 'about_vision', 'value' => 'Menjadi technology partner terpercaya yang membantu transformasi digital bisnis Indonesia melalui solusi software berkualitas tinggi.', 'type' => 'textarea', 'group' => 'about'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['title' => 'Custom Software Development', 'short_description' => 'Pembuatan sistem custom berdasarkan proses bisnis Anda. Dari analisis kebutuhan hingga deployment.', 'icon' => '⚙️', 'features' => ['Business Process Analysis', 'Custom Architecture', 'Full-stack Development', 'Testing & QA', 'Deployment & Support']],
            ['title' => 'Web Application', 'short_description' => 'Aplikasi web profesional dan scalable untuk bisnis modern. Responsive, fast, dan secure.', 'icon' => '🌐', 'features' => ['Responsive Design', 'Modern Tech Stack', 'API-first Architecture', 'Performance Optimized']],
            ['title' => 'SaaS Development', 'short_description' => 'Membangun SaaS dari MVP sampai production. Multi-tenant, scalable, dan market-ready.', 'icon' => '☁️', 'features' => ['Multi-tenant Architecture', 'Subscription Billing', 'User Management', 'Analytics Dashboard']],
            ['title' => 'AI Development', 'short_description' => 'Integrasi AI, chatbot, AI Agent, knowledge base dan automation untuk efisiensi bisnis.', 'icon' => '🤖', 'features' => ['AI Chatbot', 'AI Agent', 'Knowledge Base', 'Natural Language Processing', 'Predictive Analytics']],
            ['title' => 'Business Automation', 'short_description' => 'Mengurangi pekerjaan manual melalui automasi proses bisnis yang efisien.', 'icon' => '⚡', 'features' => ['Workflow Automation', 'Data Processing', 'Report Generation', 'Integration']],
            ['title' => 'System Integration', 'short_description' => 'Integrasi API dan sistem untuk konektivitas data yang seamless antar platform.', 'icon' => '🔗', 'features' => ['API Development', 'Third-party Integration', 'Data Sync', 'Microservices']],
            ['title' => 'Website Development', 'short_description' => 'Website premium untuk perusahaan dan bisnis. SEO-friendly, fast, dan elegan.', 'icon' => '💻', 'features' => ['Premium Design', 'SEO Optimized', 'Performance First', 'CMS Integration']],
            ['title' => 'IT Consulting', 'short_description' => 'Konsultasi architecture, software dan digital transformation untuk bisnis Anda.', 'icon' => '📊', 'features' => ['Architecture Review', 'Technology Assessment', 'Digital Strategy', 'Technical Advisory']],
        ];

        foreach ($services as $index => $service) {
            Service::create(array_merge($service, [
                'slug' => \Illuminate\Support\Str::slug($service['title']),
                'sort_order' => $index,
                'is_published' => true,
            ]));
        }
    }

    private function seedSolutions(): void
    {
        $solutions = [
            ['title' => 'Inventory System', 'short_description' => 'Sistem manajemen inventory real-time untuk tracking stok, warehouse, dan supply chain.', 'icon' => '📦'],
            ['title' => 'CRM', 'short_description' => 'Customer Relationship Management untuk mengelola leads, pipeline, dan customer data.', 'icon' => '👥'],
            ['title' => 'HR System', 'short_description' => 'Sistem HR untuk karyawan, payroll, absensi, dan manajemen data personalia.', 'icon' => '🏢'],
            ['title' => 'Finance System', 'short_description' => 'Sistem akuntansi dan keuangan untuk invoicing, expense tracking, dan reporting.', 'icon' => '💰'],
            ['title' => 'ERP', 'short_description' => 'Enterprise Resource Planning untuk integrasi seluruh operasional bisnis dalam satu platform.', 'icon' => '🎛️'],
            ['title' => 'Dashboard', 'short_description' => 'Business intelligence dashboard untuk visualisasi data dan pengambilan keputusan.', 'icon' => '📊'],
            ['title' => 'AI Customer Service', 'short_description' => 'AI-powered customer service dengan chatbot, knowledge base, dan auto-response.', 'icon' => '🤖'],
            ['title' => 'AI Agent', 'short_description' => 'Autonomous AI agent untuk automation tugas kompleks dan decision-making.', 'icon' => '🧠'],
            ['title' => 'Business Automation', 'short_description' => 'Automasi workflow bisnis untuk mengurangi manual work dan meningkatkan efisiensi.', 'icon' => '⚡'],
            ['title' => 'Custom System', 'short_description' => 'Sistem custom yang dirancang khusus berdasarkan proses bisnis spesifik perusahaan Anda.', 'icon' => '🔧'],
        ];

        foreach ($solutions as $index => $solution) {
            Solution::create(array_merge($solution, [
                'slug' => \Illuminate\Support\Str::slug($solution['title']),
                'sort_order' => $index,
                'is_published' => true,
            ]));
        }
    }

    private function seedPortfolioCategories(): void
    {
        $categories = ['Web Application', 'Business System', 'SaaS', 'AI', 'Automation', 'Website', 'Mobile App', 'API Integration'];

        foreach ($categories as $index => $category) {
            PortfolioCategory::create([
                'name' => $category,
                'slug' => \Illuminate\Support\Str::slug($category),
                'sort_order' => $index,
            ]);
        }
    }

    private function seedPortfolios(): void
    {
        $demoPortfolios = [
            [
                'title' => 'Demo — Inventory Management System',
                'short_description' => 'Sistem manajemen inventory real-time dengan fitur warehouse management, stock tracking, dan reporting.',
                'description' => 'Demo project: Inventory Management System yang dibangun untuk industri manufaktur.',
                'category_id' => PortfolioCategory::where('slug', 'business-system')->first()->id,
                'client' => 'Demo Project',
                'year' => '2024',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
                'challenge' => 'Perusahaan manufaktur membutuhkan sistem inventory yang real-time untuk mengelola stok di multiple warehouse.',
                'solution' => 'Membangun sistem inventory dengan real-time tracking, barcode scanning, dan automated reorder alerts.',
                'results' => 'Efisiensi pengelolaan inventory meningkat secara signifikan dengan sistem yang terintegrasi.',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title' => 'Demo — AI Customer Service Chatbot',
                'short_description' => 'AI-powered chatbot dengan knowledge base untuk customer service otomatis.',
                'description' => 'Demo project: AI Chatbot yang dibangun dengan NLP untuk customer service.',
                'category_id' => PortfolioCategory::where('slug', 'ai')->first()->id,
                'client' => 'Demo Project',
                'year' => '2024',
                'technologies' => ['Python', 'OpenAI', 'FastAPI', 'React'],
                'challenge' => 'Perusahaan membutuhkan customer service yang tersedia 24/7 untuk menjawab pertanyaan pelanggan.',
                'solution' => 'Mengembangkan AI chatbot dengan knowledge base yang terintegrasi dengan sistem CRM.',
                'results' => 'Response time berkurang drastis dan customer satisfaction meningkat.',
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Demo — Business Automation Dashboard',
                'short_description' => 'Dashboard monitoring untuk mengotomasi workflow bisnis dengan analytics real-time.',
                'description' => 'Demo project: Business automation dashboard untuk monitoring KPI.',
                'category_id' => PortfolioCategory::where('slug', 'automation')->first()->id,
                'client' => 'Demo Project',
                'year' => '2024',
                'technologies' => ['Laravel', 'Tailwind CSS', 'Alpine.js', 'MySQL'],
                'challenge' => 'Tim manajemen membutuhkan dashboard yang menampilkan KPI real-time dari berbagai departemen.',
                'solution' => 'Membangun dashboard dengan data aggregation, visualisasi charts, dan automated reporting.',
                'results' => 'Decision making menjadi lebih cepat dan akurat dengan data real-time.',
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($demoPortfolios as $portfolio) {
            Portfolio::create($portfolio);
        }
    }

    private function seedProcessSteps(): void
    {
        $steps = [
            ['step_number' => 1, 'title' => 'Consultation', 'description' => 'Diskusi mendalam tentang kebutuhan bisnis dan goals yang ingin dicapai.'],
            ['step_number' => 2, 'title' => 'Business Analysis', 'description' => 'Analisis proses bisnis, pain points, dan requirement gathering.'],
            ['step_number' => 3, 'title' => 'System Design', 'description' => 'Perancangan arsitektur system, database design, dan UI/UX design.'],
            ['step_number' => 4, 'title' => 'Development', 'description' => 'Proses development dengan agile methodology dan regular updates.'],
            ['step_number' => 5, 'title' => 'Testing', 'description' => 'Quality assurance, user acceptance testing, dan performance testing.'],
            ['step_number' => 6, 'title' => 'Deployment', 'description' => 'Deployment ke production server dengan zero-downtime strategy.'],
            ['step_number' => 7, 'title' => 'Support', 'description' => 'Ongoing support, maintenance, dan iterative improvement.'],
        ];

        foreach ($steps as $index => $step) {
            ProcessStep::create(array_merge($step, [
                'sort_order' => $index,
                'is_published' => true,
            ]));
        }
    }

    private function seedTestimonials(): void
    {
        // No fake testimonials — admin will add real ones
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['question' => 'Berapa lama waktu pembuatan sistem custom?', 'answer' => 'Durasi project tergantung kompleksitas. Simple system bisa 2-4 minggu, enterprise system bisa 3-6 bulan. Kami akan memberikan estimasi yang akurat setelah konsultasi.', 'category' => 'General', 'sort_order' => 0],
            ['question' => 'Apakah bisa request perubahan setelah project selesai?', 'answer' => 'Ya, kami menyediakan maintenance dan support pasca-deployment. Perubahan kecil biasanya termasuk dalam package support. Perubahan besar akan dinilai dan diestimasi terpisah.', 'category' => 'General', 'sort_order' => 1],
            ['question' => 'Teknologi apa yang digunakan?', 'answer' => 'Kami menggunakan teknologi modern seperti Laravel, React, Vue.js, Python, MySQL, dan lainnya. Pilihan teknologi disesuaikan dengan kebutuhan project.', 'category' => 'Technical', 'sort_order' => 2],
            ['question' => 'Bagaimana cara memulai project?', 'answer' => 'Hubungi kami via WhatsApp atau form kontak. Kami akan menjadwalkan konsultasi gratis untuk memahami kebutuhan bisnis Anda.', 'category' => 'General', 'sort_order' => 3],
            ['question' => 'Apakah bisa integrasi dengan sistem yang sudah ada?', 'answer' => 'Ya, kami berpengalaman dalam integrasi API dan sistem existing. Kami akan menganalisis sistem yang ada dan merancang integrasi yang seamless.', 'category' => 'Technical', 'sort_order' => 4],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_published' => true]));
        }
    }

    private function seedBlogCategories(): void
    {
        $categories = ['AI', 'SaaS', 'Software Development', 'Digital Transformation', 'Automation', 'Business', 'IT', 'Technology'];

        foreach ($categories as $index => $category) {
            BlogCategory::create([
                'name' => $category,
                'slug' => \Illuminate\Support\Str::slug($category),
                'sort_order' => $index,
            ]);
        }
    }

    private function seedBlogTags(): void
    {
        $tags = ['Laravel', 'React', 'AI', 'Chatbot', 'SaaS', 'Automation', 'Digital Transformation', 'PHP', 'Python', 'Vue.js'];

        foreach ($tags as $tag) {
            BlogTag::create([
                'name' => $tag,
                'slug' => \Illuminate\Support\Str::slug($tag),
            ]);
        }
    }

    private function seedBlogPosts(): void
    {
        // No fake blog posts — admin will create real content
    }

    private function seedCeoProfile(): void
    {
        CeoProfile::create([
            'name' => 'Deni Afrizal',
            'position' => 'CEO & System/Application Developer',
            'short_bio' => 'Deni Afrizal adalah profesional IT yang memulai karier sebagai developer dan berkembang menjadi IT Project Manager. Kombinasi antara software engineering, system architecture, business process analysis, dan project management.',
            'full_bio' => "Deni Afrizal adalah profesional IT yang memulai karier sebagai developer dan berkembang menjadi IT Project Manager. Pengalaman tersebut memberikan kombinasi antara kemampuan software engineering, system architecture, business process analysis, dan project management.\n\nDengan fokus pada custom software development, SaaS, AI, dan business automation, Deni membantu bisnis membangun sistem digital yang sesuai dengan kebutuhan operasional mereka.\n\nVisinya adalah membantu transformasi digital bisnis Indonesia melalui solusi software berkualitas tinggi yang dirancang berdasarkan pemahaman mendalam terhadap proses bisnis.",
            'skills' => ['Software Development', 'System Architecture', 'SaaS Development', 'AI & Machine Learning', 'Business Automation', 'IT Project Management', 'Business Process Analysis', 'Laravel', 'PHP', 'Python'],
            'experience' => ['Software Developer', 'Full-stack Developer', 'IT Project Manager', 'CEO & System Architect — Aldef Tech'],
            'email' => 'deni@aldeftech.com',
            'is_active' => true,
        ]);
    }

    private function seedHomepageSections(): void
    {
        $sections = [
            ['section_key' => 'hero', 'title' => 'Hero Section', 'is_visible' => true, 'sort_order' => 0],
            ['section_key' => 'trust', 'title' => 'Trust / Value Proposition', 'is_visible' => true, 'sort_order' => 1],
            ['section_key' => 'services', 'title' => 'Services Section', 'is_visible' => true, 'sort_order' => 2],
            ['section_key' => 'featured_portfolio', 'title' => 'Featured Portfolio', 'is_visible' => true, 'sort_order' => 3],
            ['section_key' => 'process', 'title' => 'Process Section', 'is_visible' => true, 'sort_order' => 4],
            ['section_key' => 'ceo', 'title' => 'CEO Profile Section', 'is_visible' => true, 'sort_order' => 5],
            ['section_key' => 'testimonials', 'title' => 'Testimonials', 'is_visible' => true, 'sort_order' => 6],
            ['section_key' => 'faq', 'title' => 'FAQ Section', 'is_visible' => true, 'sort_order' => 7],
            ['section_key' => 'final_cta', 'title' => 'Final CTA', 'is_visible' => true, 'sort_order' => 8],
        ];

        foreach ($sections as $section) {
            HomepageSection::create($section);
        }
    }

    private function seedNavigation(): void
    {
        $menuItems = [
            ['label' => 'Home', 'url' => '/', 'sort_order' => 0, 'is_active' => true, 'location' => 'main'],
            ['label' => 'Services', 'url' => '/services', 'sort_order' => 1, 'is_active' => true, 'location' => 'main'],
            ['label' => 'Solutions', 'url' => '/solutions', 'sort_order' => 2, 'is_active' => true, 'location' => 'main'],
            ['label' => 'Portfolio', 'url' => '/portfolio', 'sort_order' => 3, 'is_active' => true, 'location' => 'main'],
            ['label' => 'About', 'url' => '/about', 'sort_order' => 4, 'is_active' => true, 'location' => 'main'],
            ['label' => 'Insights', 'url' => '/blog', 'sort_order' => 5, 'is_active' => true, 'location' => 'main'],
            ['label' => 'Contact', 'url' => '/contact', 'sort_order' => 6, 'is_active' => true, 'location' => 'main'],
        ];

        foreach ($menuItems as $item) {
            NavigationMenu::create($item);
        }
    }
}
