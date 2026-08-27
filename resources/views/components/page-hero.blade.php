@props([
    'eyebrow' => null,
    'title' => null,
    'accent' => null,     // trailing words rendered in italic serif champagne
    'lead' => null,
    'align' => 'center',  // center | left
    'compact' => false,
    'breadcrumbs' => [],  // [['label' => 'Blog', 'url' => '...'], ['label' => 'Judul']]
])

<section class="surface-obsidian relative overflow-hidden {{ $compact ? 'pt-28 pb-14 lg:pt-36 lg:pb-20' : 'pt-32 pb-20 lg:pt-44 lg:pb-28' }}">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>
    <div class="bloom bloom-gold w-[34rem] h-[34rem] -top-52 {{ $align === 'center' ? 'left-1/2 -translate-x-1/2' : '-left-32' }}" aria-hidden="true"></div>
    <div class="bloom bloom-aurora w-[28rem] h-[28rem] top-10 -right-32 opacity-40" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="{{ $align === 'center' ? 'max-w-3xl mx-auto text-center' : 'max-w-3xl' }}">

            @if(!empty($breadcrumbs))
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-graphite-400 {{ $align === 'center' ? 'justify-center' : '' }} reveal" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-gold-300 transition-colors duration-300">Beranda</a>
                @foreach($breadcrumbs as $crumb)
                    <span class="text-graphite-500" aria-hidden="true">/</span>
                    @if(!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}" class="hover:text-gold-300 transition-colors duration-300">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-gold-300 truncate max-w-[16rem]">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endif

            @if($eyebrow)
                <p class="eyebrow eyebrow-light {{ $align === 'center' ? 'eyebrow-center' : '' }} reveal">{{ $eyebrow }}</p>
            @endif

            @if($title)
            <h1 class="mt-6 text-[2.25rem] leading-[1.1] sm:text-[2.75rem] lg:text-[3.5rem] text-white reveal reveal-d1">
                {{ $title }}@if($accent) <span class="accent-serif accent-champagne">{{ $accent }}</span>@endif
            </h1>
            @endif

            @if($lead)
            <p class="mt-6 text-base lg:text-lg leading-relaxed text-graphite-300 {{ $align === 'center' ? 'max-w-2xl mx-auto' : 'max-w-2xl' }} reveal reveal-d2">
                {{ $lead }}
            </p>
            @endif

            @if(trim($slot ?? '') !== '')
            <div class="mt-9 reveal reveal-d3">
                {{ $slot }}
            </div>
            @endif
        </div>
    </div>
</section>
