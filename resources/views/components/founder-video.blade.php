@props(['src'])

<div class="founder-video-launch">
    <a href="{{ $src }}" id="founder-video-trigger" class="btn btn-obsidian"
       aria-haspopup="dialog" aria-controls="founder-video-dialog">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5v14l11-7z" />
        </svg>
        <span>{{ __('pages.about.founder_video.watch') }}</span>
    </a>
</div>

<dialog id="founder-video-dialog" aria-labelledby="founder-video-title"
        aria-describedby="founder-video-hint">
    <div class="founder-video-header">
        <h2 id="founder-video-title">{{ __('pages.about.founder_video.title') }}</h2>
        <button type="button" id="founder-video-close" autofocus
                aria-label="{{ __('pages.about.founder_video.close') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </div>
    <video id="founder-video-player" src="{{ $src }}" controls muted playsinline preload="metadata"
           aria-label="{{ __('pages.about.founder_video.title') }}">
        <a href="{{ $src }}">{{ __('pages.about.founder_video.download') }}</a>
    </video>
    <p id="founder-video-hint">{{ __('pages.about.founder_video.hint') }}</p>
    <p id="founder-video-error" role="status" hidden>{{ __('pages.about.founder_video.error') }}</p>
</dialog>

@push('styles')
<style>
    .founder-video-launch { margin-top: 1.5rem; text-align: center; }
    #founder-video-dialog {
        width: min(52rem, calc(100vw - 2rem)); max-width: none;
        max-height: calc(100dvh - 2rem); margin: auto; padding: 0;
        border: 1px solid #b89b60; border-radius: 1rem;
        background: #151719; color: #fff;
        box-shadow: 0 24px 80px rgb(0 0 0 / 40%); overflow: auto;
    }
    #founder-video-dialog::backdrop { background: rgb(0 0 0 / 75%); }
    .founder-video-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem; }
    #founder-video-title { margin: 0; font-size: 1.125rem; color: #fff; }
    #founder-video-close {
        display: grid; place-items: center; flex-shrink: 0; width: 44px; height: 44px;
        border: 1px solid #68615a; border-radius: 50%; background: transparent; color: #fff; cursor: pointer;
    }
    #founder-video-close:hover { background: #34302a; }
    #founder-video-close:focus-visible { outline: 2px solid #e0c58e; outline-offset: 3px; }
    #founder-video-player { display: block; width: 100%; max-height: 65dvh; background: #000; object-fit: contain; }
    #founder-video-hint, #founder-video-error { margin: 0; padding: 1rem; font-size: .875rem; color: #e0d5bf; }
</style>
@endpush

@push('scripts')
<script>
(() => {
    const dialog = document.getElementById('founder-video-dialog');
    const video = document.getElementById('founder-video-player');
    const trigger = document.getElementById('founder-video-trigger');
    if (!dialog || typeof dialog.showModal !== 'function') return;

    // Keep the dialog outside the founder section's scroll-reveal transforms.
    document.body.append(dialog);
    let previousFocus;
    let previousOverflow;

    const openVideo = (automatic = false) => {
        if (dialog.open) return;
        previousFocus = document.activeElement;
        previousOverflow = document.body.style.overflow;
        dialog.showModal();
        document.body.style.overflow = 'hidden';
        if (automatic) video.muted = true;
        video.play().catch(() => {
            // The native play control remains available if autoplay is blocked.
        });
    };

    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openVideo();
    });
    document.getElementById('founder-video-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        const bounds = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right
            || event.clientY < bounds.top || event.clientY > bounds.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => {
        video.pause();
        document.body.style.overflow = previousOverflow;
        if (previousFocus instanceof HTMLElement) previousFocus.focus({ preventScroll: true });
    });
    video.addEventListener('error', () => {
        document.getElementById('founder-video-error').hidden = false;
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => openVideo(true), { once: true });
    } else {
        openVideo(true);
    }
})();
</script>
@endpush
