{{-- Floating WhatsApp Interactive Widget --}}
@php
    $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.default_number', '628128968609'));
    $email = \App\Models\SiteSetting::get('email', 'info@aldeftech.com');
    $defaultMsg = 'Halo Aldef Tech, saya ingin berkonsultasi mengenai kebutuhan pengembangan software/sistem untuk bisnis saya.';
    $waUrl = 'https://wa.me/' . preg_replace('/\D/', '', $whatsappNumber) . '?text=' . rawurlencode($defaultMsg);
@endphp

<div class="wa-widget" x-data="{ open: false }">
    {{-- Popover Card (Hidden by default, shown when clicked) --}}
    <div x-show="open" x-cloak style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-3 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
         @click.outside="open = false"
         class="absolute bottom-16 right-0 mb-2 w-80 sm:w-96 bg-white border border-slate-200 rounded-2xl shadow-2xl p-5 z-50">
        
        {{-- Card Header --}}
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 status-dot-pulse"></div>
                <div>
                    <h4 class="text-sm font-display font-bold text-slate-900 leading-tight">Aldef Tech Support</h4>
                    <p class="text-[0.6875rem] text-slate-500 font-medium">Fast Response Consultation</p>
                </div>
            </div>
            <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors" aria-label="Close popup">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body Text --}}
        <div class="py-3 text-xs text-slate-600 leading-relaxed">
            Punya kebutuhan custom software, SaaS, integrasi sistem, atau AI automation? Hubungi kami langsung melalui WhatsApp untuk konsultasi cepat.
        </div>

        {{-- Contact Action Options --}}
        <div class="space-y-2 pt-1">
            <a href="{{ $waUrl }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50/80 hover:bg-emerald-100/80 border border-emerald-200/80 transition-colors group">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Chat via WhatsApp</div>
                    <div class="text-[0.6875rem] text-slate-500 truncate">+{{ preg_replace('/\D/', '', $whatsappNumber) }}</div>
                </div>
                <span class="text-xs font-semibold text-emerald-600">Mulai →</span>
            </a>

            <a href="{{ route('contact') }}"
               class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors group">
                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Kirim Formulir Project</div>
                    <div class="text-[0.6875rem] text-slate-500">Estimasi spesifikasi & scope</div>
                </div>
                <span class="text-xs font-semibold text-blue-600">Isi Form →</span>
            </a>
        </div>
    </div>

    {{-- Main Float Button --}}
    <button @click="open = !open" type="button" class="wa-float-btn relative" aria-label="Toggle WhatsApp Consultation popup">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        {{-- Notification pulse --}}
        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white status-dot-pulse"></span>
    </button>
</div>
