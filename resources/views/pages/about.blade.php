@extends('layouts.app')

@php
    use App\Models\SiteSetting;

    $pageTitle = 'Tentang Aldef Tech — Mitra Transformasi Digital Korporasi';
    $metaDescription = SiteSetting::get('about_subtitle', 'Aldef Tech adalah mitra teknologi yang merancang dan membangun sistem digital, platform SaaS, dan automasi AI untuk perusahaan di Indonesia.');

    $aboutTitle    = SiteSetting::get('about_title', 'Tentang Aldef Tech');
    $aboutSubtitle = SiteSetting::get('about_subtitle');
    $mission       = SiteSetting::get('about_mission');
    $vision        = SiteSetting::get('about_vision');

    $values = [
        [
            'icon'  => 'compass',
            'title' => 'Jujur soal ruang lingkup',
            'body'  => 'Kami menolak pekerjaan yang tidak yakin bisa kami selesaikan dengan baik, dan mengatakannya sejak awal — bukan setelah kontrak ditandatangani.',
        ],
        [
            'icon'  => 'blueprint',
            'title' => 'Sederhana yang bertahan',
            'body'  => 'Solusi paling sederhana yang menyelesaikan masalah hampir selalu lebih baik daripada yang paling canggih. Kompleksitas adalah biaya, bukan prestasi.',
        ],
        [
            'icon'  => 'lock',
            'title' => 'Tanpa mengunci klien',
            'body'  => 'Kode, basis data, dan dokumentasi menjadi milik Anda. Jika suatu saat ingin pindah tim, kami pastikan transisinya mudah.',
        ],
        [
            'icon'  => 'clock',
            'title' => 'Menghormati waktu Anda',
            'body'  => 'Kabar buruk disampaikan lebih cepat daripada kabar baik. Anda tidak akan menunggu laporan untuk tahu ada yang meleset.',
        ],
    ];

    $timeline = [
        ['year' => 'Fondasi',   'title' => 'Rekayasa perangkat lunak', 'body' => 'Berawal dari pengerjaan sistem internal perusahaan — sistem absensi, inventaris, dan operasional yang dipakai harian.'],
        ['year' => 'Perluasan', 'title' => 'Platform & SaaS',          'body' => 'Merambah pembangunan platform multi-tenant, booking engine, POS omnichannel, dan sistem OTA untuk klien lintas industri.'],
        ['year' => 'Hari ini',  'title' => 'AI & automasi',            'body' => 'Menggabungkan LLM, pemrosesan dokumen, dan automasi proses ke dalam sistem bisnis yang sudah berjalan.'],
    ];
@endphp

@section('content')

{{-- The title is editable from the admin, so no accent clause is appended here:
     it would run on into whatever the admin typed. --}}
<x-page-hero
    eyebrow="Tentang Kami"
    :title="$aboutTitle"
    :lead="$aboutSubtitle"
    :edge="false"
    :breadcrumbs="[['label' => 'Tentang']]">
    <p class="font-serif-accent italic text-xl sm:text-2xl accent-spectrum">
        “Mitra Transformasi Digital Korporasi Anda”
    </p>
</x-page-hero>

{{-- ── Brand plate ──────────────────────────────────────────────────────── --}}
<section class="surface-spectrum spectrum-edge relative overflow-hidden pb-16 lg:pb-24">
    <div class="shell relative z-10">
        <figure class="max-w-4xl mx-auto frame-banner reveal-scale" data-tilt="2.5">
            <img src="{{ asset('images/aldef-tech-banner.png') }}"
                 alt="Aldef Tech — pengembangan sistem, aplikasi, kecerdasan buatan, dan solusi IT"
                 width="1376" height="768" loading="lazy" decoding="async">
        </figure>
    </div>
</section>

{{-- ── Mission & vision ─────────────────────────────────────────────────── --}}
@if($mission || $vision)
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6" data-reveal-group="120">
            @if($mission)
            <article class="card-lux reveal group p-8 lg:p-10">
                <span class="icon-plate"><x-lux-icon name="target" /></span>
                <h2 class="mt-6 text-2xl">Misi</h2>
                <p class="mt-4 text-[0.9375rem] leading-[1.8] text-graphite-600">{{ $mission }}</p>
            </article>
            @endif

            @if($vision)
            <article class="card-lux reveal group p-8 lg:p-10">
                <span class="icon-plate"><x-lux-icon name="rocket" /></span>
                <h2 class="mt-6 text-2xl">Visi</h2>
                <p class="mt-4 text-[0.9375rem] leading-[1.8] text-graphite-600">{{ $vision }}</p>
            </article>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ── Founder ──────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <div class="lg:col-span-5 reveal-left">
                <figure class="frame-lux aspect-[4/5] max-w-sm mx-auto lg:max-w-none lg:sticky lg:top-28">
                    <img src="{{ media_url($ceoProfile->profile_photo ?? null, 'images/deni-afrizal.jpg') }}"
                         alt="{{ $ceoProfile->name }}" loading="lazy" decoding="async">
                </figure>
            </div>

            <div class="lg:col-span-7 reveal-right">
                <p class="eyebrow">Pendiri</p>

                <h2 class="mt-5 text-3xl sm:text-4xl">{{ $ceoProfile->name }}</h2>
                <p class="mt-2 text-sm text-gold-700 font-display font-semibold tracking-wide">{{ $ceoProfile->position }}</p>

                @if(!empty($ceoProfile->short_bio))
                <blockquote class="mt-8 font-serif-accent italic text-xl sm:text-2xl leading-[1.45] text-graphite-900 border-l-2 border-gold-500 pl-6">
                    {{ $ceoProfile->short_bio }}
                </blockquote>
                @endif

                @if(!empty($ceoProfile->full_bio))
                <p class="mt-8 text-[0.9375rem] leading-[1.85] text-graphite-700">{{ $ceoProfile->full_bio }}</p>
                @endif

                @if(!empty($ceoProfile->skills))
                <div class="mt-9">
                    <p class="eyebrow mb-4">Bidang Keahlian</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach((array) $ceoProfile->skills as $skill)
                            <span class="chip chip-neutral">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($ceoProfile->experience))
                <div class="mt-9">
                    <p class="eyebrow mb-5">Pengalaman</p>
                    <ul class="space-y-3">
                        @foreach((array) $ceoProfile->experience as $exp)
                        <li class="feature-row">
                            <span class="tick">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ is_array($exp) ? ($exp['title'] ?? reset($exp)) : $exp }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    @if(!empty($ceoProfile->linkedin))
                        <a href="{{ $ceoProfile->linkedin }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><span>LinkedIn</span></a>
                    @endif
                    @if(!empty($ceoProfile->github))
                        <a href="{{ $ceoProfile->github }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><span>GitHub</span></a>
                    @endif
                    @if(!empty($ceoProfile->email))
                        <a href="mailto:{{ $ceoProfile->email }}" class="btn btn-outline btn-sm"><span>Email</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Values ───────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">Prinsip Kerja</p>
            <h2 class="mt-5 text-3xl sm:text-4xl">
                Empat hal yang tidak kami <span class="accent-serif accent-gold">tawar</span>.
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6" data-reveal-group="90">
            @foreach($values as $value)
            <article class="card-lux reveal group p-7 lg:p-8 !flex-row items-start gap-5">
                <span class="icon-plate"><x-lux-icon :name="$value['icon']" /></span>
                <div class="min-w-0">
                    <h3 class="text-lg">{{ $value['title'] }}</h3>
                    <p class="mt-2.5 text-sm leading-relaxed text-graphite-600">{{ $value['body'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Journey ──────────────────────────────────────────────────────────── --}}
<section class="section-padding surface-obsidian relative overflow-hidden">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-gold w-[34rem] h-[34rem] -top-48 right-0 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <header class="max-w-2xl reveal">
            <p class="eyebrow eyebrow-light">Perjalanan</p>
            <h2 class="mt-5 text-3xl sm:text-4xl text-white">
                Dibangun dari pekerjaan nyata, bukan dari <span class="accent-serif accent-champagne">rencana bisnis.</span>
            </h2>
        </header>

        <ol class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="100">
            @foreach($timeline as $item)
            <li class="card-obsidian reveal group p-7 lg:p-8">
                <span class="chip chip-dark">{{ $item['year'] }}</span>
                <h3 class="mt-5 text-lg text-white">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-graphite-400">{{ $item['body'] }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

<x-cta-band
    eyebrow="Berkenalan"
    title="Mari bicara sebelum"
    accent="bicara harga."
    lead="Kami lebih suka memahami persoalannya dulu. Kalau ternyata Anda tidak butuh sistem baru, kami akan bilang begitu." />

@endsection
