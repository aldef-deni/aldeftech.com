@extends('layouts.app')

@php
    use App\Models\SiteSetting;
    use App\Services\WhatsAppService;

    $pageTitle = __('pages.contact.meta_title');
    $metaDescription = __('pages.contact.meta_description');

    $email    = SiteSetting::get('email', 'aldeftech@gmail.com');
    $waNumber = SiteSetting::get('whatsapp_number', '+62 812-8968-609');
    $address  = SiteSetting::get('address', 'Rumah Chiara 2, Jl. Curug Induk, Bojong Kulur, Kec. Gunung Putri, Kab. Bogor');
    $mapsUrl  = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);

    $projectTypes  = config('aldeftech.lead.project_types', []);
    $budgetRanges  = config('aldeftech.lead.budget_ranges', []);
@endphp

@section('content')

<x-page-hero
    :eyebrow="__('pages.contact.eyebrow')"
    :title="__('pages.contact.title')"
    :accent="__('pages.contact.accent')"
    :lead="__('pages.contact.lead')"
    :breadcrumbs="[['label' => __('site.nav.contact')]]" />

<section class="section-padding surface-ivory relative">
    <div class="absolute inset-0 veil-grid-light pointer-events-none" aria-hidden="true"></div>

    <div class="shell relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

            {{-- ── Form ─────────────────────────────────────────────────── --}}
            <div class="lg:col-span-7">
                <div class="card-lux p-7 sm:p-9 lg:p-10 reveal">

                    @if(session('error'))
                    <div class="mb-8 rounded-xl border border-rust-500/30 bg-rust-100 px-5 py-4">
                        <p class="flex items-start gap-3 text-sm font-medium text-[#8E4232]">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </p>
                        @if(session('whatsapp_url'))
                        <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm mt-4">
                            <span>{{ __('pages.contact.form.contact_wa') }}</span>
                            <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="mb-8 rounded-xl border border-jade-500/30 bg-jade-100 px-5 py-4">
                        <p class="flex items-start gap-3 text-sm font-medium text-[#2E6E56]">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </p>
                        @if(session('whatsapp_url'))
                        <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm mt-4">
                            <span>{{ __('pages.contact.form.continue_wa') }}</span>
                            <svg class="btn-arrow w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif

                    <p class="eyebrow">{{ __('pages.contact.form.eyebrow') }}</p>
                    <h2 class="mt-4 text-2xl lg:text-[1.75rem]">{{ __('pages.contact.form.title') }}</h2>
                    <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                        {{ __('pages.contact.form.subtitle') }}
                    </p>

                    <hr class="rule-fade my-8">

                    <form method="POST" action="{{ lroute('contact.store') }}" class="space-y-5" novalidate>
                        @csrf

                        {{-- Spam traps. Neither is visible to a visitor, and
                             neither can reject a submission on its own — they
                             only feed the score, so a false positive costs an
                             editor one click rather than costing a real lead.

                             The field is named "website_url" because that is
                             what an automated form-filler goes looking for.
                             aria-hidden and tabindex keep it away from anyone
                             using a screen reader or the keyboard. --}}
                        <div class="hidden" aria-hidden="true">
                            <label for="website_url">Jangan isi kolom ini</label>
                            <input type="text" id="website_url" name="website_url"
                                   tabindex="-1" autocomplete="off">
                        </div>

                        {{-- Encrypted so the timestamp cannot simply be rewritten. --}}
                        <input type="hidden" name="form_started_at" value="{{ encrypt(now()->timestamp) }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="form-label">{{ __('pages.contact.form.name') }} <span class="required">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       autocomplete="name" placeholder="{{ __('pages.contact.form.name_ph') }}"
                                       class="form-input @error('name') has-error @enderror">
                                @error('name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="company" class="form-label">{{ __('pages.contact.form.company') }}</label>
                                <input type="text" id="company" name="company" value="{{ old('company') }}"
                                       autocomplete="organization" placeholder="{{ __('pages.contact.form.company_ph') }}"
                                       class="form-input @error('company') has-error @enderror">
                                @error('company')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="email" class="form-label">{{ __('pages.contact.form.email') }} <span class="required">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       autocomplete="email" placeholder="{{ __('pages.contact.form.email_ph') }}"
                                       class="form-input @error('email') has-error @enderror">
                                @error('email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="whatsapp" class="form-label">{{ __('pages.contact.form.whatsapp') }}</label>
                                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                                       autocomplete="tel" placeholder="{{ __('pages.contact.form.whatsapp_ph') }}"
                                       class="form-input @error('whatsapp') has-error @enderror">
                                @error('whatsapp')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="project_type" class="form-label">{{ __('pages.contact.form.type') }}</label>
                                <select id="project_type" name="project_type" class="form-input @error('project_type') has-error @enderror">
                                    <option value="">{{ __('pages.contact.form.type_ph') }}</option>
                                    @foreach($projectTypes as $type)
                                        <option value="{{ $type }}" @selected(old('project_type') === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('project_type')<p class="form-error">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="budget_range" class="form-label">{{ __('pages.contact.form.budget') }}</label>
                                <select id="budget_range" name="budget_range" class="form-input @error('budget_range') has-error @enderror">
                                    <option value="">{{ __('pages.contact.form.budget_ph') }}</option>
                                    @foreach($budgetRanges as $range)
                                        <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ $range }}</option>
                                    @endforeach
                                </select>
                                @error('budget_range')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="form-label">{{ __('pages.contact.form.message') }} <span class="required">*</span></label>
                            <textarea id="message" name="message" rows="6" required
                                      placeholder="{{ __('pages.contact.form.message_ph') }}"
                                      class="form-input resize-y @error('message') has-error @enderror">{{ old('message') }}</textarea>
                            @error('message')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center gap-4">
                            <button type="submit" class="btn btn-primary btn-lg w-full sm:w-auto">
                                <span>{{ __('pages.contact.form.submit') }}</span>
                                <svg class="btn-arrow w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                            <p class="text-xs text-graphite-400 text-center sm:text-left leading-relaxed">
                                {{ __('pages.contact.form.privacy') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Direct channels ──────────────────────────────────────── --}}
            <aside class="lg:col-span-5">
                <div class="lg:sticky lg:top-28 cards-swipe md:space-y-5" data-reveal-group="90">

                    <a href="{{ WhatsAppService::getUrl() }}" target="_blank" rel="noopener"
                       class="card-lux card-lux-featured reveal group p-7 !flex-row items-start gap-5">
                        <span class="icon-plate">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </span>
                        <span class="min-w-0">
                            <span class="block font-display text-base font-semibold text-graphite-900">{{ __('pages.contact.channels.whatsapp') }}</span>
                            <span class="block text-sm text-graphite-600 mt-1 tabular">{{ $waNumber }}</span>
                            <span class="block text-xs text-gold-700 mt-2.5 font-medium">{{ __('pages.contact.channels.whatsapp_hint') }}</span>
                        </span>
                    </a>

                    <a href="mailto:{{ $email }}" class="card-lux reveal group p-7 !flex-row items-start gap-5">
                        <span class="icon-plate"><x-lux-icon name="mail" /></span>
                        <span class="min-w-0">
                            <span class="block font-display text-base font-semibold text-graphite-900">{{ __('pages.contact.channels.email') }}</span>
                            <span class="block text-sm text-graphite-600 mt-1 break-all">{{ $email }}</span>
                            <span class="block text-xs text-graphite-400 mt-2.5">{{ __('pages.contact.channels.email_hint') }}</span>
                        </span>
                    </a>

                    <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="card-lux reveal group p-7 !flex-row items-start gap-5">
                        <span class="icon-plate"><x-lux-icon name="compass" /></span>
                        <span class="min-w-0">
                            <span class="block font-display text-base font-semibold text-graphite-900">{{ __('pages.contact.channels.location') }}</span>
                            <span class="block text-sm text-graphite-600 mt-1 leading-relaxed">{{ $address }}</span>
                            <span class="block text-xs text-gold-700 mt-2.5 font-medium">{{ __('pages.contact.channels.maps') }}</span>
                        </span>
                    </a>

                    <div class="card-quiet reveal p-7">
                        <p class="eyebrow mb-5">{{ __('pages.contact.channels.next_eyebrow') }}</p>
                        <ol class="space-y-4">
                            @foreach((array) __('pages.contact.channels.steps') as $n => $stepText)
                            <li class="flex items-start gap-4">
                                <span class="font-serif-accent italic text-xl text-gold-600 leading-none shrink-0 pt-0.5">{{ str_pad($n + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-sm leading-relaxed text-graphite-600">{{ $stepText }}</span>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection
