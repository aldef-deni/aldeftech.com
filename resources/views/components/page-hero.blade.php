@props([
    'eyebrow' => null,
    'title' => null,
    'accent' => null,     // trailing words rendered in italic serif, logo spectrum
    'lead' => null,
    'align' => 'center',  // center | left
    'compact' => false,
    'breadcrumbs' => [],  // [['label' => 'Blog', 'url' => '...'], ['label' => 'Judul']]
    'edge' => true,       // false when the band continues into the next section
])

<section class="surface-spectrum {{ $edge ? 'spectrum-edge' : '' }} relative overflow-hidden {{ $compact ? 'pt-32 pb-14 lg:pt-40 lg:pb-20' : 'pt-36 pb-20 lg:pt-48 lg:pb-28' }}">
    <div class="absolute inset-0 veil-grid pointer-events-none" aria-hidden="true"></div>

    {{-- Depth on top of the CSS wash: warm hues left, cool right, mirroring the mark. --}}
    <div class="bloom bloom-ember w-[26rem] h-[26rem] -top-56 {{ $align === 'center' ? 'left-[2%]' : '-left-44' }} opacity-30" aria-hidden="true"></div>
    <div class="bloom bloom-magenta w-[24rem] h-[24rem] -top-52 left-[46%] -translate-x-1/2 opacity-25" aria-hidden="true"></div>
    <div class="bloom bloom-violet w-[30rem] h-[30rem] -top-52 right-0 opacity-35" aria-hidden="true"></div>
    <div class="bloom bloom-azure w-[26rem] h-[26rem] top-16 -right-44 opacity-26" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="{{ $align === 'center' ? 'max-w-3xl mx-auto text-center' : 'max-w-3xl' }}">

            @if(!empty($breadcrumbs))
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-graphite-400 {{ $align === 'center' ? 'justify-center' : '' }} reveal" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-spectrum-violet-soft transition-colors duration-300">Beranda</a>
                @foreach($breadcrumbs as $crumb)
                    <span class="text-graphite-500" aria-hidden="true">/</span>
                    @if(!empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}" class="hover:text-spectrum-violet-soft transition-colors duration-300">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-spectrum-violet-soft truncate max-w-[16rem]">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
            @endif

            @if($eyebrow)
                <p class="eyebrow eyebrow-spectrum {{ $align === 'center' ? 'eyebrow-center' : '' }} reveal">{{ $eyebrow }}</p>
            @endif

            @if($title)
            <h1 class="mt-6 text-[2.25rem] leading-[1.1] sm:text-[2.75rem] lg:text-[3.5rem] text-white reveal reveal-d1">
                {{ $title }}@if($accent) <span class="accent-serif accent-spectrum">{{ $accent }}</span>@endif
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
