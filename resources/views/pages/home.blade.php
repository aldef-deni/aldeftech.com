@extends('layouts.app')

@php
    use App\Services\WhatsAppService;
    use App\Models\SiteSetting;

    $pageTitle = SiteSetting::get('seo_default_title', 'Aldef Tech — Software Engineering, SaaS & AI untuk Korporasi');
    $metaDescription = SiteSetting::get('seo_default_description', 'Aldef Tech membangun sistem custom, aplikasi web, platform SaaS, dan automasi AI untuk perusahaan. Mitra transformasi digital korporasi Anda.');

    $waUrl = WhatsAppService::getUrl();

    $stack = [
        'Laravel 13', 'PHP 8.3', 'Python', 'OpenAI & AI Agents', 'Vue.js 3', 'React',
        'Tailwind CSS', 'PostgreSQL', 'MySQL', 'Redis', 'Docker', 'REST & GraphQL',
        'AWS', 'Kubernetes', 'Nginx', 'CI/CD',
    ];

    $pillars = [
        [
            'icon'  => 'blueprint',
            'title' => 'Arsitektur dulu, kode kemudian',
            'body'  => 'Setiap proyek dimulai dari pemetaan proses bisnis dan desain arsitektur. Sistem yang dibangun di atas fondasi yang benar tidak perlu ditulis ulang dua tahun lagi.',
        ],
        [
            'icon'  => 'lock',
            'title' => 'Kepemilikan penuh atas kode',
            'body'  => 'Source code, database, dan dokumentasi menjadi milik Anda sepenuhnya. Tidak ada vendor lock-in, tidak ada biaya lisensi tersembunyi.',
        ],
        [
            'icon'  => 'chart',
            'title' => 'Diukur dari hasil bisnis',
            'body'  => 'Kami menetapkan metrik keberhasilan sejak awal — waktu proses yang dipangkas, biaya operasional yang turun, kapasitas yang naik.',
        ],
        [
            'icon'  => 'lifebuoy',
            'title' => 'Pendampingan setelah rilis',
            'body'  => 'Rilis bukan garis akhir. Monitoring, perbaikan, dan pengembangan lanjutan berjalan dengan SLA yang jelas dan terukur.',
        ],
    ];
@endphp

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════
     HERO
     ══════════════════════════════════════════════════════════════════ --}}
<section class="surface-spectrum spectrum-edge relative overflow-hidden pt-36 pb-0 lg:pt-48">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-ember w-[30rem] h-[30rem] -top-64 -left-48 opacity-30" aria-hidden="true"></div>
    <div class="bloom bloom-magenta w-[26rem] h-[26rem] -top-60 left-[28%] opacity-25" aria-hidden="true"></div>
    <div class="bloom bloom-violet w-[34rem] h-[34rem] -top-60 right-[2%] opacity-35" aria-hidden="true"></div>
    <div class="bloom bloom-azure w-[30rem] h-[30rem] top-10 -right-52 opacity-28" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="max-w-4xl mx-auto text-center">

            <p class="eyebrow eyebrow-center eyebrow-spectrum reveal">Software Engineering · AI · Automation</p>

            <h1 class="mt-7 text-[2.5rem] leading-[1.08] sm:text-5xl lg:text-[4rem] text-white reveal reveal-d1">
                Sistem digital yang benar-benar
                <span class="accent-serif accent-spectrum">menggerakkan</span>
                bisnis Anda.
            </h1>

            <p class="mt-7 text-base sm:text-lg leading-relaxed text-graphite-300 max-w-2xl mx-auto reveal reveal-d2">
                Aldef Tech merancang dan membangun sistem custom, aplikasi web, platform SaaS,
                serta automasi berbasis AI — dirakit untuk menyelesaikan persoalan operasional nyata,
                bukan sekadar memenuhi checklist fitur.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3.5 reveal reveal-d3">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg w-full sm:w-auto magnetic" data-magnetic="0.1">
                    <span>Konsultasi Proyek Gratis</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('portfolio') }}" class="btn btn-ghost btn-lg w-full sm:w-auto">
                    <span>Lihat Portofolio</span>
                </a>
            </div>

            <p class="mt-5 inline-flex items-center gap-2.5 text-xs text-graphite-400 reveal reveal-d4">
                <span class="pulse-dot"></span>
                Respons rata-rata di bawah 2 jam pada hari kerja
            </p>
        </div>

        {{-- Brand key visual --}}
        <div class="mt-14 lg:mt-20 max-w-5xl mx-auto reveal-scale reveal-d3" data-tilt="3">
            <figure class="frame-banner">
                <img src="{{ asset('images/aldef-tech-banner.png') }}"
                     alt="Aldef Tech — pengembangan sistem, aplikasi, kecerdasan buatan, dan solusi IT"
                     width="1376" height="768" fetchpriority="high" decoding="async">
            </figure>
        </div>

        {{-- Trust metrics --}}
        <div class="max-w-4xl mx-auto mt-14 lg:mt-16 grid grid-cols-2 sm:grid-cols-4 gap-y-9 gap-x-6 pb-16 lg:pb-20"
             data-reveal-group="90">
            @foreach([
                ['v' => '10', 'suffix' => '+',  'l' => 'Tahun rekayasa perangkat lunak'],
                ['v' => '40', 'suffix' => '+',  'l' => 'Sistem & aplikasi dirilis'],
                ['v' => '99.9', 'suffix' => '%', 'l' => 'Target ketersediaan layanan', 'dec' => 1],
                ['v' => '100', 'suffix' => '%', 'l' => 'Kode menjadi milik klien'],
            ] as $m)
            <div class="text-center reveal">
                <p class="stat-value stat-value-light tabular">
                    <span data-counter="{{ $m['v'] }}" data-counter-suffix="{{ $m['suffix'] }}" data-counter-decimals="{{ $m['dec'] ?? 0 }}">0</span>
                </p>
                <p class="stat-label">{{ $m['l'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     TECH STACK MARQUEE
     ══════════════════════════════════════════════════════════════════ --}}
<section class="surface-ivory-deep border-y border-line py-6 lg:py-7" aria-label="Teknologi yang kami gunakan">
    <div class="marquee">
        <div class="marquee-track">
            @foreach(array_merge($stack, $stack) as $tech)
                <span class="marquee-item">{{ $tech }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     SERVICES
     ══════════════════════════════════════════════════════════════════ --}}
@if($services->isNotEmpty())
<section id="layanan" class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow">Kapabilitas</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                Satu mitra untuk seluruh siklus
                <span class="accent-serif accent-gold">rekayasa</span> perangkat lunak.
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                Dari analisis proses bisnis, desain arsitektur, pembangunan, hingga pendampingan pasca-rilis —
                ditangani oleh satu tim yang memahami konteks bisnis Anda seutuhnya.
            </p>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="70">
            @foreach($services as $i => $service)
            <article class="card-lux reveal group p-7 lg:p-8">
                <div class="flex items-start justify-between gap-4">
                    <span class="icon-plate">
                        <x-lux-icon :name="$service->icon" />
                    </span>
                    <span class="font-serif-accent italic text-2xl text-line leading-none pt-1 transition-colors duration-500 group-hover:text-gold-300">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <h3 class="mt-6 text-lg lg:text-xl">{{ $service->title }}</h3>

                <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                    {{ excerpt_text($service->short_description, 155) }}
                </p>

                @if(!empty($service->features))
                <ul class="mt-6 space-y-2.5">
                    @foreach(array_slice((array) $service->features, 0, 4) as $feature)
                    <li class="feature-row">
                        <span class="tick">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="mt-auto pt-7">
                    <a href="{{ route('services') }}" class="link-arrow">
                        <span>Pelajari</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-12 text-center reveal">
            <a href="{{ route('services') }}" class="btn btn-outline">
                <span>Lihat seluruh layanan</span>
                <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     DIFFERENTIATORS
     ══════════════════════════════════════════════════════════════════ --}}
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">

            <div class="lg:col-span-5 lg:sticky lg:top-32 lg:self-start reveal-left">
                <p class="eyebrow">Mengapa Aldef Tech</p>
                <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                    Bukan sekadar menulis kode —
                    kami membangun <span class="accent-serif accent-gold">aset</span> perusahaan.
                </h2>
                <p class="mt-6 text-base leading-relaxed text-graphite-600">
                    Sistem yang baik memangkas biaya operasional selama bertahun-tahun setelah dibayar sekali.
                    Sistem yang buruk menjadi beban yang terus tumbuh. Perbedaannya ditentukan
                    di ruang perencanaan, jauh sebelum baris pertama ditulis.
                </p>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ route('about') }}" class="btn btn-obsidian">
                        <span>Kenali cara kerja kami</span>
                        <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-4" data-reveal-group="90">
                @foreach($pillars as $i => $pillar)
                <article class="card-lux reveal group p-6 lg:p-8 !flex-row items-start gap-5">
                    <span class="icon-plate">
                        <x-lux-icon :name="$pillar['icon']" />
                    </span>
                    <div class="min-w-0">
                        <h3 class="text-base lg:text-lg">{{ $pillar['title'] }}</h3>
                        <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">{{ $pillar['body'] }}</p>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════
     PROCESS
     ══════════════════════════════════════════════════════════════════ --}}
@if($processSteps->isNotEmpty())
<section id="proses" class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">Metode Kerja</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                Alur yang <span class="accent-serif accent-gold">terukur</span>, tanpa kejutan di tengah jalan.
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                Setiap tahap punya keluaran yang dapat ditinjau, sehingga Anda selalu tahu posisi proyek.
            </p>
        </header>

        <ol class="mt-14 lg:mt-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10"
            data-reveal-group="70">
            @foreach($processSteps as $step)
            <li class="reveal group relative pt-7">
                <span class="absolute top-0 left-0 right-0 h-px bg-line transition-colors duration-700 group-hover:bg-gold-400" aria-hidden="true"></span>

                <span class="step-numeral block">{{ str_pad($step->step_number ?? $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                <h3 class="mt-4 text-base lg:text-lg">{{ $step->title }}</h3>
                <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">
                    {{ excerpt_text($step->description, 130) }}
                </p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     PORTFOLIO
     ══════════════════════════════════════════════════════════════════ --}}
@if($portfolios->isNotEmpty())
<section id="portofolio" class="section-padding surface-obsidian relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-aurora w-[36rem] h-[36rem] -top-32 -right-40 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 reveal">
            <div class="max-w-2xl">
                <p class="eyebrow eyebrow-light">Pekerjaan Terpilih</p>
                <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem] text-white">
                    Sistem yang sudah <span class="accent-serif accent-champagne">berjalan</span> di lapangan.
                </h2>
            </div>
            <a href="{{ route('portfolio') }}" class="link-arrow link-arrow-light shrink-0">
                <span>Semua proyek</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="90">
            @foreach($portfolios as $item)
            <a href="{{ route('portfolio.show', $item->slug) }}" class="card-obsidian reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-white/10 aspect-[16/10] bg-ink-800">
                    @if($src = media_url($item->featured_image))
                        <img src="{{ $src }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-500/30">
                            <x-lux-icon name="layers" class="w-12 h-12" />
                        </span>
                    @endif
                </div>

                <div class="p-6 lg:p-7 flex-1 flex flex-col">
                    <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-400">
                        <span>{{ $item->category->name ?? 'Proyek' }}</span>
                        @if($item->year)
                            <span class="w-1 h-1 rounded-full bg-gold-600" aria-hidden="true"></span>
                            <span class="tabular">{{ $item->year }}</span>
                        @endif
                    </div>

                    <h3 class="mt-3.5 text-lg text-white">{{ $item->title }}</h3>

                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-400">
                        {{ excerpt_text($item->short_description, 120) }}
                    </p>

                    @if(!empty($item->technologies))
                    <div class="mt-5 flex flex-wrap gap-1.5">
                        @foreach(array_slice((array) $item->technologies, 0, 4) as $tech)
                            <span class="px-2.5 py-1 rounded-md text-[0.6875rem] font-medium bg-white/5 border border-white/10 text-graphite-300">{{ $tech }}</span>
                        @endforeach
                    </div>
                    @endif

                    <span class="mt-auto pt-6 link-arrow link-arrow-light">
                        <span>Lihat studi kasus</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     SOLUTIONS
     ══════════════════════════════════════════════════════════════════ --}}
@if($solutions->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">Solusi Siap Disesuaikan</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                Fondasi yang sudah <span class="accent-serif accent-gold">terbukti</span>, disesuaikan dengan proses Anda.
            </h2>
            <p class="mt-5 text-base leading-relaxed text-graphite-600">
                Mulai dari kerangka yang matang, lalu dibentuk mengikuti alur kerja perusahaan Anda —
                bukan sebaliknya.
            </p>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"
             data-reveal-group="50">
            @foreach($solutions as $solution)
            <a href="{{ route('solutions') }}" class="card-quiet reveal group p-6 flex flex-col">
                <span class="icon-plate icon-plate-sm">
                    <x-lux-icon :name="$solution->icon" />
                </span>
                <h3 class="mt-4 text-[0.9375rem] leading-snug">{{ $solution->title }}</h3>
                <p class="mt-2 text-[0.8125rem] leading-relaxed text-graphite-500">
                    {{ excerpt_text($solution->short_description, 78) }}
                </p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     LEADERSHIP
     ══════════════════════════════════════════════════════════════════ --}}
@if($ceoProfile)
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

            <div class="lg:col-span-5 reveal-left">
                <figure class="frame-lux aspect-[4/5] max-w-sm mx-auto lg:max-w-none">
                    @if($src = media_url($ceoProfile->profile_photo, 'images/deni-afrizal.jpg'))
                        <img src="{{ $src }}" alt="{{ $ceoProfile->name }}" loading="lazy" decoding="async">
                    @endif
                </figure>
            </div>

            <div class="lg:col-span-7 reveal-right">
                <p class="eyebrow">Kepemimpinan</p>

                <blockquote class="mt-6 font-serif-accent italic text-2xl sm:text-3xl lg:text-[2.125rem] leading-[1.35] text-graphite-900">
                    “{{ excerpt_text($ceoProfile->short_bio, 210) ?: 'Teknologi hanya bernilai ketika ia menyederhanakan pekerjaan orang yang menggunakannya setiap hari.' }}”
                </blockquote>

                <div class="mt-8 flex items-center gap-4">
                    <span class="w-10 h-px bg-gold-500" aria-hidden="true"></span>
                    <div>
                        <p class="font-display text-base font-semibold text-graphite-900">{{ $ceoProfile->name }}</p>
                        <p class="text-sm text-graphite-500 mt-0.5">{{ $ceoProfile->position }}</p>
                    </div>
                </div>

                @if(!empty($ceoProfile->skills))
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach(array_slice((array) $ceoProfile->skills, 0, 8) as $skill)
                        <span class="chip chip-neutral">{{ $skill }}</span>
                    @endforeach
                </div>
                @endif

                <div class="mt-9">
                    <a href="{{ route('about') }}" class="link-arrow">
                        <span>Profil lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     TESTIMONIALS
     ══════════════════════════════════════════════════════════════════ --}}
@if($testimonials->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">Suara Klien</p>
            <h2 class="mt-5 text-3xl sm:text-4xl lg:text-[2.75rem]">
                Dipercaya untuk pekerjaan yang <span class="accent-serif accent-gold">kritikal</span>.
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6"
             data-reveal-group="80">
            @foreach($testimonials->take(6) as $t)
            <figure class="card-lux reveal p-7 lg:p-8">
                @if($t->rating)
                <div class="flex gap-1 text-gold-500" aria-label="{{ $t->rating }} dari 5">
                    @for($s = 1; $s <= 5; $s++)
                        <svg class="w-3.5 h-3.5 {{ $s <= $t->rating ? 'opacity-100' : 'opacity-25' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L1.5 7.7l5.9-.9z"/></svg>
                    @endfor
                </div>
                @endif

                <blockquote class="mt-5 text-[0.9375rem] leading-relaxed text-graphite-700">
                    “{{ excerpt_text($t->testimonial, 260) }}”
                </blockquote>

                <figcaption class="mt-7 pt-6 border-t border-line-soft flex items-center gap-3.5">
                    @if($src = media_url($t->photo))
                        <img src="{{ $src }}" alt="{{ $t->client_name }}" class="w-11 h-11 rounded-full object-cover border border-line" loading="lazy">
                    @else
                        <span class="w-11 h-11 rounded-full bg-gold-100 border border-gold-200 text-gold-700 font-display text-sm font-semibold flex items-center justify-center shrink-0">
                            {{ initials_of($t->client_name) }}
                        </span>
                    @endif
                    <span class="min-w-0">
                        <span class="block font-display text-sm font-semibold text-graphite-900 truncate">{{ $t->client_name }}</span>
                        <span class="block text-xs text-graphite-500 truncate">{{ trim(($t->position ? $t->position . ' · ' : '') . $t->company, ' ·') }}</span>
                    </span>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     FAQ
     ══════════════════════════════════════════════════════════════════ --}}
@if($faqs->isNotEmpty())
<section class="section-padding surface-ivory-deep border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

            <div class="lg:col-span-4 reveal-left">
                <p class="eyebrow">Pertanyaan Umum</p>
                <h2 class="mt-5 text-3xl sm:text-4xl">
                    Hal yang paling sering <span class="accent-serif accent-gold">ditanyakan</span>.
                </h2>
                <p class="mt-5 text-base leading-relaxed text-graphite-600">
                    Belum terjawab? Kirim pertanyaan Anda langsung — kami balas secara personal.
                </p>
                <a href="{{ route('faq') }}" class="link-arrow mt-7">
                    <span>Semua pertanyaan</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="lg:col-span-8 space-y-3" x-data="{ open: 0 }" data-reveal-group="60">
                @foreach($faqs->take(6) as $i => $faq)
                <div class="accordion-item reveal" :class="open === {{ $i }} && 'is-open'">
                    <button type="button" class="accordion-trigger"
                            @click="open = open === {{ $i }} ? null : {{ $i }}"
                            :aria-expanded="(open === {{ $i }}).toString()">
                        <span>{{ $faq->question }}</span>
                        <span class="accordion-marker" aria-hidden="true">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                        </span>
                    </button>
                    <div class="accordion-panel">
                        <div>
                            <div class="px-6 pb-6 -mt-1 text-sm leading-relaxed text-graphite-600">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     INSIGHTS
     ══════════════════════════════════════════════════════════════════ --}}
@if($latestPosts->isNotEmpty())
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 reveal">
            <div class="max-w-2xl">
                <p class="eyebrow">Insight</p>
                <h2 class="mt-5 text-3xl sm:text-4xl">Catatan dari ruang kerja kami.</h2>
            </div>
            <a href="{{ route('blog') }}" class="link-arrow shrink-0">
                <span>Semua tulisan</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </header>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="80">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="card-lux reveal group overflow-hidden">
                <div class="frame-lux !rounded-none !border-0 !border-b !border-line aspect-[16/10]">
                    @if($src = media_url($post->featured_image))
                        <img src="{{ $src }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="absolute inset-0 flex items-center justify-center text-gold-400/40">
                            <x-lux-icon name="code" class="w-10 h-10" />
                        </span>
                    @endif
                </div>
                <div class="p-6 lg:p-7 flex-1 flex flex-col">
                    <div class="flex items-center gap-2.5 text-[0.6875rem] uppercase tracking-[0.14em] text-gold-700">
                        <span>{{ $post->category->name ?? 'Insight' }}</span>
                        @if($post->published_at)
                            <span class="w-1 h-1 rounded-full bg-gold-400" aria-hidden="true"></span>
                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d M Y') }}</time>
                        @endif
                    </div>
                    <h3 class="mt-3.5 text-lg leading-snug">{{ $post->title }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">{{ excerpt_text($post->excerpt, 120) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════════════
     CLOSING CTA
     ══════════════════════════════════════════════════════════════════ --}}
<section id="kontak" class="surface-spectrum-deep relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-ember w-[26rem] h-[26rem] -bottom-60 left-[2%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-magenta w-[28rem] h-[28rem] -bottom-68 left-[32%] opacity-28" aria-hidden="true"></div>
    <div class="bloom bloom-violet w-[32rem] h-[32rem] -bottom-72 right-[20%] opacity-36" aria-hidden="true"></div>
    <div class="bloom bloom-azure w-[26rem] h-[26rem] -bottom-56 -right-32 opacity-25" aria-hidden="true"></div>

    <div class="shell relative z-10 py-20 lg:py-28">
        <div class="max-w-3xl mx-auto text-center">
            <p class="eyebrow eyebrow-center eyebrow-spectrum reveal">Langkah Berikutnya</p>

            <h2 class="mt-7 text-3xl sm:text-4xl lg:text-[3rem] leading-[1.12] text-white reveal reveal-d1">
                Ceritakan persoalannya.
                Kami bantu <span class="accent-serif accent-spectrum">petakan</span> solusinya.
            </h2>

            <p class="mt-6 text-base lg:text-lg leading-relaxed text-graphite-300 reveal reveal-d2">
                Sesi konsultasi awal tanpa biaya — hasilnya berupa gambaran ruang lingkup,
                pendekatan teknis, dan estimasi yang bisa Anda bawa ke rapat internal.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3.5 reveal reveal-d3">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg w-full sm:w-auto magnetic" data-magnetic="0.1">
                    <span>Mulai lewat WhatsApp</span>
                    <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('contact') }}" class="btn btn-ghost btn-lg w-full sm:w-auto">
                    <span>Kirim brief proyek</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
