@php
    $containerFooter = isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
        ? 'container-xxl'
        : 'container-fluid';
@endphp

<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column gap-2">
            <div class="text-body">
                &copy; {{ date('Y') }}
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="footer-link fw-medium">{{ config('app.name', 'Aldef Tech') }}</a>
                — Admin Console
            </div>

            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="footer-link">Lihat Situs</a>
                <a href="{{ route('admin.settings.site') }}" class="footer-link">Pengaturan</a>
                <a href="{{ route('admin.activity-logs.index') }}" class="footer-link d-none d-lg-inline">Log Aktivitas</a>
            </div>
        </div>
    </div>
</footer>
