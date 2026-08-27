@extends('layouts.app')

@section('content')

<x-page-hero
    eyebrow="Layanan"
    title="Kapabilitas rekayasa untuk setiap tahap"
    accent="pertumbuhan."
    lead="Dari sistem internal yang merapikan operasional, hingga platform SaaS yang menjadi produk itu sendiri — semuanya dibangun dengan standar arsitektur yang sama.">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5">
        <a href="{{ \App\Services\WhatsAppService::getUrl() }}" target="_blank" rel="noopener" class="btn btn-primary w-full sm:w-auto">
            <span>Diskusikan kebutuhan Anda</span>
            <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        <a href="{{ route('portfolio') }}" class="btn btn-ghost w-full sm:w-auto"><span>Lihat hasil kerja</span></a>
    </div>
</x-page-hero>

{{-- ── Service list ─────────────────────────────────────────────────────── --}}
<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10 space-y-5 lg:space-y-6">
        @foreach($services as $i => $service)
        <article id="{{ $service->slug ?? \Illuminate\Support\Str::slug($service->title) }}"
                 class="card-lux reveal group scroll-mt-28 p-7 sm:p-8 lg:p-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-7 lg:gap-12 items-start">

                {{-- Heading side --}}
                <div class="lg:col-span-5">
                    <div class="flex items-start gap-5">
                        <span class="icon-plate">
                            <x-lux-icon :name="$service->icon" />
                        </span>
                        <span class="font-serif-accent italic text-3xl text-line leading-none pt-1.5 transition-colors duration-700 group-hover:text-gold-300">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <h2 class="mt-6 text-2xl lg:text-[1.75rem] leading-tight">{{ $service->title }}</h2>

                    <p class="mt-4 text-[0.9375rem] leading-relaxed text-graphite-600">
                        {{ $service->short_description }}
                    </p>

                </div>

                {{-- Detail side --}}
                <div class="lg:col-span-7 lg:border-l lg:border-line-soft lg:pl-12">
                    @if(!empty($service->description))
                    <p class="text-[0.9375rem] leading-[1.8] text-graphite-700">
                        {{ $service->description }}
                    </p>
                    @endif

                    @if(!empty($service->features))
                    <p class="eyebrow {{ !empty($service->description) ? 'mt-7' : '' }} mb-5">Yang Anda dapatkan</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3.5">
                        @foreach((array) $service->features as $feature)
                        <li class="feature-row">
                            <span class="tick">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-line-soft">
                <a href="{{ \App\Services\WhatsAppService::getProjectUrl($service->title) }}"
                   target="_blank" rel="noopener" class="link-arrow">
                    <span>Konsultasikan layanan ini</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </article>
        @endforeach
    </div>
</section>

{{-- ── Engagement models ────────────────────────────────────────────────── --}}
<section class="section-padding surface-parchment border-y border-line">
    <div class="shell">
        <header class="max-w-2xl mx-auto text-center reveal">
            <p class="eyebrow eyebrow-center">Model Kerja Sama</p>
            <h2 class="mt-5 text-3xl sm:text-4xl">
                Pilih bentuk kolaborasi yang paling <span class="accent-serif accent-gold">masuk akal</span>.
            </h2>
        </header>

        <div class="mt-12 lg:mt-16 grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-6" data-reveal-group="90">
            @foreach([
                [
                    'icon' => 'target',
                    'name' => 'Proyek Terikat Ruang Lingkup',
                    'best' => 'Kebutuhan sudah jelas',
                    'body' => 'Ruang lingkup, jadwal, dan biaya disepakati di muka. Cocok ketika Anda sudah tahu persis sistem apa yang dibutuhkan.',
                    'points' => ['Harga tetap, tanpa kejutan', 'Milestone dengan serah terima jelas', 'Garansi perbaikan pasca-rilis'],
                ],
                [
                    'icon' => 'users',
                    'name' => 'Tim Khusus (Dedicated)',
                    'best' => 'Pengembangan berkelanjutan',
                    'body' => 'Engineer yang bekerja khusus untuk Anda dengan ritme bulanan. Prioritas bisa berubah mengikuti kebutuhan bisnis.',
                    'points' => ['Kapasitas yang dapat diprediksi', 'Prioritas fleksibel tiap sprint', 'Laporan progres mingguan'],
                    'featured' => true,
                ],
                [
                    'icon' => 'compass',
                    'name' => 'Pendampingan Teknis',
                    'best' => 'Sudah punya tim sendiri',
                    'body' => 'Audit arsitektur, tinjauan kode, dan arahan teknis untuk tim internal Anda tanpa mengambil alih pekerjaan.',
                    'points' => ['Audit arsitektur & keamanan', 'Peta jalan teknis', 'Pendampingan tim internal'],
                ],
            ] as $model)
            <article class="card-lux reveal group p-7 lg:p-8 {{ !empty($model['featured']) ? 'card-lux-featured' : '' }}">
                @if(!empty($model['featured']))
                    <span class="chip self-start mb-5">Paling sering dipilih</span>
                @else
                    <span class="chip chip-neutral self-start mb-5">{{ $model['best'] }}</span>
                @endif

                <span class="icon-plate">
                    <x-lux-icon :name="$model['icon']" />
                </span>

                <h3 class="mt-5 text-lg lg:text-xl">{{ $model['name'] }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-graphite-600">{{ $model['body'] }}</p>

                <ul class="mt-6 space-y-2.5">
                    @foreach($model['points'] as $point)
                    <li class="feature-row">
                        <span class="tick">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>
            @endforeach
        </div>

        <p class="mt-10 text-center text-sm text-graphite-500 reveal">
            Belum yakin yang mana? Ceritakan situasinya — kami sarankan yang paling hemat untuk Anda.
        </p>
    </div>
</section>

<x-cta-band
    eyebrow="Mulai Percakapan"
    title="Layanan mana yang paling"
    accent="mendesak bagi Anda?"
    lead="Sampaikan kendalanya. Kami balas dengan pendekatan konkret, bukan brosur." />

@endsection
