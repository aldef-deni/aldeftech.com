@php
    use App\Models\SiteSetting;
    use App\Services\WhatsAppService;

    $waNumber = preg_replace('/\D/', '', SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.number', '628128968609')));
    $waUrl    = WhatsAppService::getUrl();
@endphp

<div class="wa-widget" x-data="{ open: false }" @keydown.escape.window="open = false">

    {{-- Panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-[cubic-bezier(.22,1,.36,1)] duration-400"
         x-transition:enter-start="opacity-0 translate-y-4 scale-[0.97]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-3 scale-[0.97]"
         @click.outside="open = false"
         class="absolute bottom-[4.25rem] right-0 w-[min(21rem,calc(100vw-3rem))] rounded-2xl border border-line bg-ivory-50/97 backdrop-blur-2xl shadow-[0_40px_80px_-32px_rgba(13,20,32,0.45)] overflow-hidden"
         role="dialog" aria-label="{{ __('site.widget.open_panel') }}">

        <div class="surface-obsidian px-5 py-4 flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="pulse-dot mt-1.5"></span>
                <div>
                    <p class="font-display text-sm font-semibold text-white leading-tight">Aldef Tech</p>
                    <p class="text-[0.6875rem] text-gold-300 mt-0.5">{{ __('site.widget.reply_time') }}</p>
                </div>
            </div>
            <button @click="open = false" type="button"
                    class="text-graphite-400 hover:text-white transition-colors duration-300 p-1 -m-1 rounded-lg"
                    aria-label="{{ __('site.widget.close') }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-4 space-y-2.5">
            <p class="text-xs text-graphite-600 leading-relaxed px-1 pb-1">
                {{ __('site.widget.intro') }}
            </p>

            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="card-quiet flex items-center gap-3 p-3 group">
                <span class="icon-plate icon-plate-sm">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </span>
                <span class="flex-1 min-w-0">
                    <span class="block font-display text-xs font-semibold text-graphite-900">{{ __('site.widget.chat') }}</span>
                    <span class="block text-[0.6875rem] text-graphite-400 tabular truncate">+{{ $waNumber }}</span>
                </span>
                <svg class="w-4 h-4 text-gold-600 shrink-0 transition-transform duration-500 ease-[cubic-bezier(.22,1,.36,1)] group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>

            <a href="{{ route('contact') }}" class="card-quiet flex items-center gap-3 p-3 group">
                <span class="icon-plate icon-plate-sm">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <span class="flex-1 min-w-0">
                    <span class="block font-display text-xs font-semibold text-graphite-900">{{ __('site.widget.send_brief') }}</span>
                    <span class="block text-[0.6875rem] text-graphite-400">{{ __('site.widget.brief_hint') }}</span>
                </span>
                <svg class="w-4 h-4 text-gold-600 shrink-0 transition-transform duration-500 ease-[cubic-bezier(.22,1,.36,1)] group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Trigger --}}
    <button @click="open = !open" type="button" class="wa-btn relative"
            :aria-expanded="open.toString()" aria-label="{{ __('site.widget.open_panel') }}">
        <svg x-show="!open" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <svg x-show="open" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
