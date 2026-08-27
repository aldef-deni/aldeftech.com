@extends('layouts.layoutMaster')

@section('title', 'FAQ')

@section('content')

<x-admin.page-head
    eyebrow="Konten Situs"
    title="FAQ"
    subtitle="{{ $faqs->count() }} pertanyaan · {{ $faqs->where('is_published', true)->count() }} tampil di situs">
    <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
        <i class="icon-base ti tabler-plus me-2"></i>Tambah Pertanyaan
    </a>
</x-admin.page-head>

<div class="card">
    @if($faqs->isEmpty())
        <div class="card-body">
            <x-admin.empty
                icon="tabler-help-circle"
                title="Belum ada pertanyaan"
                message="FAQ yang baik memangkas pertanyaan berulang di WhatsApp.">
                <a href="{{ route('admin.faq.create') }}" class="btn btn-primary">
                    <i class="icon-base ti tabler-plus me-2"></i>Tambah Pertanyaan
                </a>
            </x-admin.empty>
        </div>
    @else
    <div class="accordion accordion-flush" id="faqList">
        @foreach($faqs as $faq)
        <div class="accordion-item">
            <div class="d-flex align-items-center gap-2 pe-3">
                <h2 class="accordion-header flex-grow-1 min-w-0" id="faqHead{{ $faq->id }}">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#faqBody{{ $faq->id }}"
                            aria-expanded="false" aria-controls="faqBody{{ $faq->id }}">
                        <span class="text-truncate">{{ $faq->question }}</span>
                    </button>
                </h2>

                <div class="d-flex align-items-center gap-2 text-nowrap">
                    @if($faq->category)<span class="badge bg-label-secondary d-none d-md-inline">{{ $faq->category }}</span>@endif
                    <x-admin.status :published="$faq->is_published" />
                    <a href="{{ route('admin.faq.edit', $faq) }}" class="btn btn-sm btn-icon btn-text-secondary" title="Ubah">
                        <i class="icon-base ti tabler-pencil"></i>
                    </a>
                    <x-admin.delete
                        :action="route('admin.faq.destroy', $faq)"
                        confirm="Hapus pertanyaan ini?" />
                </div>
            </div>

            <div id="faqBody{{ $faq->id }}" class="accordion-collapse collapse"
                 aria-labelledby="faqHead{{ $faq->id }}" data-bs-parent="#faqList">
                <div class="accordion-body text-body-secondary">{{ $faq->answer }}</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
