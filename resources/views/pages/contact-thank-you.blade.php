@extends('layouts.app')

@php
    use App\Services\WhatsAppService;

    $noindex = true;
    $analyticsLeadConversion = $leadConversion;
    $isEnglish = app()->isLocale('en');
    $waUrl = WhatsAppService::getUrl();
    $pageTitle = $isEnglish ? 'Brief Received — Aldef Tech' : 'Brief Diterima — Aldef Tech';
    $metaDescription = $isEnglish
        ? 'Your project brief has been received by Aldef Tech.'
        : 'Brief proyek Anda sudah diterima oleh Aldef Tech.';
@endphp

@section('content')
<x-page-hero
    :eyebrow="$isEnglish ? 'Thank you' : 'Terima kasih'"
    :title="$isEnglish ? 'Your brief is in.' : 'Brief Anda sudah kami terima.'"
    :accent="$isEnglish ? 'We will be in touch.' : 'Kami akan menghubungi Anda.'"
    :lead="$isEnglish ? 'Our team will review the context you shared and follow up with the right questions. You can also continue the conversation on WhatsApp.' : 'Tim kami akan membaca konteks yang Anda kirim dan menindaklanjutinya dengan pertanyaan yang relevan. Anda juga dapat melanjutkan percakapan melalui WhatsApp.'"
    :breadcrumbs="[['label' => $isEnglish ? 'Thank you' : 'Terima kasih']]" />

<section class="section-padding surface-ivory">
    <div class="shell max-w-3xl text-center">
        <div class="card-lux card-lux-featured p-8 sm:p-10 reveal">
            <span class="icon-plate mx-auto">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="m5 12 4 4L19 6"/></svg>
            </span>
            <h2 class="mt-6 text-2xl">{{ $isEnglish ? 'Want a faster conversation?' : 'Ingin berdiskusi lebih cepat?' }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-graphite-600">
                {{ $isEnglish ? 'WhatsApp is available if you would like to add context or ask a quick question.' : 'WhatsApp tersedia jika Anda ingin menambahkan konteks atau menanyakan hal singkat.' }}
            </p>
            <div class="mt-7 flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn btn-primary"
                   data-analytics-event="whatsapp_click" data-analytics-cta-location="thank_you"
                   data-analytics-destination="whatsapp">
                    {{ $isEnglish ? 'Continue on WhatsApp' : 'Lanjutkan di WhatsApp' }}
                </a>
                <a href="{{ lroute('home') }}" class="btn btn-outline"
                   data-analytics-event="cta_click" data-analytics-cta-location="thank_you"
                   data-analytics-destination="home">
                    {{ $isEnglish ? 'Back to home' : 'Kembali ke beranda' }}
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
