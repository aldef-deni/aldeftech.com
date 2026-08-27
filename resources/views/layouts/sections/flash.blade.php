{{-- Set by the PostTooLargeException handler: the request never reached a
     session, so the reason travels in the query string instead. --}}
@if(request('upload_error') === 'too_large')
<div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
    <i class="icon-base ti tabler-alert-circle mt-1"></i>
    <div class="flex-grow-1">
        Berkas melebihi batas {{ max_upload_label() }} dan ditolak server sebelum sempat diproses.
        Kompres berkas terlebih dulu, lalu unggah ulang.
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
    <i class="icon-base ti tabler-circle-check mt-1"></i>
    <div class="flex-grow-1">{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
    <i class="icon-base ti tabler-alert-circle mt-1"></i>
    <div class="flex-grow-1">{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible d-flex align-items-start gap-2 mb-4" role="alert">
    <i class="icon-base ti tabler-alert-triangle mt-1"></i>
    <div class="flex-grow-1">{{ session('warning') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
</div>
@endif

{{-- Show the summary whenever validation failed. The earlier "> 1" guard was
     meant to avoid doubling up with inline field errors, but it silently
     swallowed single-error responses on screens that render none. --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible mb-4" role="alert">
    <div class="d-flex align-items-start gap-2">
        <i class="icon-base ti tabler-alert-circle mt-1"></i>
        <div class="flex-grow-1">
            <h6 class="alert-heading mb-2">Periksa kembali isian berikut</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
</div>
@endif
