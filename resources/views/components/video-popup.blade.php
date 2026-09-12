@props(['src', 'title', 'watch', 'poster', 'triggerClass' => 'btn-obsidian'])

<div class="intro-video-launch">
    <a href="{{ $src }}" id="intro-video-trigger" class="btn {{ $triggerClass }}"
       aria-haspopup="dialog" aria-controls="intro-video-dialog">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M8 5v14l11-7z" />
        </svg>
        <span>{{ $watch }}</span>
    </a>
</div>

<dialog id="intro-video-dialog" aria-label="{{ $title }}">
    <button type="button" id="intro-video-close" autofocus aria-label="{{ __('video.close') }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
            <path d="m6 6 12 12M18 6 6 18" />
        </svg>
    </button>
    <div class="intro-video-stage">
        <video id="intro-video-player" src="{{ $src }}" poster="{{ $poster }}"
               controls playsinline preload="metadata" aria-label="{{ $title }}"></video>
        <div id="intro-video-play-prompt" hidden>
            <button type="button" id="intro-video-play" aria-label="{{ __('video.play') }}">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </button>
        </div>
    </div>
    <p id="intro-video-error" class="intro-video-sr-only" role="status" hidden>{{ __('video.error') }}</p>
</dialog>

@push('styles')
<style>
    .intro-video-launch { margin-top: 1.5rem; text-align: center; }
    #intro-video-dialog {
        box-sizing: border-box;
        width: min(94vw, 160vh, 88rem);
        width: min(94vw, 160dvh, 88rem);
        max-width: none; max-height: 90vh; max-height: 90dvh;
        margin: auto; padding: 0; border: 1px solid rgb(224 197 142 / 42%); border-radius: 1.25rem;
        background: #08090a; color: #fff; overflow: hidden;
        box-shadow: 0 36px 120px rgb(0 0 0 / 70%), 0 0 0 6px rgb(255 255 255 / 4%), 0 0 64px rgb(198 166 104 / 10%);
    }
    #intro-video-dialog::backdrop { background: rgb(5 7 9 / 88%); backdrop-filter: blur(12px) saturate(.75); }
    #intro-video-close {
        position: absolute; z-index: 3; top: 1rem; right: 1rem;
        display: grid; place-items: center; width: 46px; height: 46px;
        border: 1px solid rgb(255 255 255 / 24%); border-radius: 50%;
        background: rgb(9 11 13 / 62%); color: #fff; cursor: pointer;
        box-shadow: 0 8px 30px rgb(0 0 0 / 35%); backdrop-filter: blur(12px);
        transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
    }
    #intro-video-close:hover { border-color: rgb(224 197 142 / 70%); background: rgb(198 166 104 / 28%); transform: scale(1.04); }
    #intro-video-close:focus-visible, #intro-video-play:focus-visible {
        outline: 2px solid #e0c58e; outline-offset: 4px;
    }
    .intro-video-stage {
        position: relative; width: 100%; aspect-ratio: 16 / 9; background: #08090a;
    }
    #intro-video-player { display: block; width: 100%; height: 100%; object-fit: contain; }
    #intro-video-play-prompt:not([hidden]) {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        padding: 1rem; background: linear-gradient(transparent, rgb(0 0 0 / 35%)); pointer-events: none;
    }
    #intro-video-play {
        display: inline-grid; place-items: center; width: 64px; height: 64px; padding: 0 0 0 4px;
        border: 1px solid #efd9ad; border-radius: 50%;
        background: linear-gradient(135deg, #eed7a7, #c6a368); color: #191714;
        cursor: pointer; pointer-events: auto;
        box-shadow: 0 8px 32px rgb(0 0 0 / 35%);
    }
    #intro-video-play:hover { filter: brightness(1.08); }
    .intro-video-sr-only {
        position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px;
        overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
    }
    @media (max-width: 640px) {
        #intro-video-dialog { width: calc(100% - 1rem); border-radius: .9rem; }
        #intro-video-close { top: .65rem; right: .65rem; width: 42px; height: 42px; }
        #intro-video-play { width: 56px; height: 56px; }
        .intro-video-launch .btn { max-width: 100%; white-space: normal; }
    }
    @media (max-height: 500px) and (orientation: landscape) {
        #intro-video-dialog { width: min(94vw, 156vh); width: min(94vw, 156dvh); max-height: 88dvh; }
        #intro-video-close { top: .5rem; right: .5rem; width: 40px; height: 40px; }
    }
    @media (prefers-reduced-motion: no-preference) {
        #intro-video-dialog[open] { animation: intro-video-enter .28s ease-out; }
        @keyframes intro-video-enter { from { opacity: 0; transform: translateY(12px) scale(.98); } to { opacity: 1; transform: none; } }
    }
</style>
@endpush

@push('scripts')
<script>
(() => {
    const dialog = document.getElementById('intro-video-dialog');
    const video = document.getElementById('intro-video-player');
    const trigger = document.getElementById('intro-video-trigger');
    const prompt = document.getElementById('intro-video-play-prompt');
    const error = document.getElementById('intro-video-error');
    if (!dialog || typeof dialog.showModal !== 'function') return;

    // Avoid the page sections' scroll-reveal transforms.
    document.body.append(dialog);
    let previousFocus;
    let previousOverflow;
    let previousRootOverflow;

    const playWithSound = () => {
        prompt.hidden = true;
        video.muted = false;
        video.defaultMuted = false;
        video.volume = 1;
        video.play().catch((failure) => {
            if (!dialog.open || failure.name === 'AbortError') return;
            if (failure.name === 'NotAllowedError') {
                prompt.hidden = false;
            } else {
                error.hidden = false;
            }
        });
    };

    const openVideo = () => {
        if (dialog.open) return;
        previousFocus = document.activeElement;
        previousOverflow = document.body.style.overflow;
        previousRootOverflow = document.documentElement.style.overflow;
        dialog.showModal();
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        video.currentTime = 0;
        playWithSound();
    };

    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openVideo();
    });
    document.getElementById('intro-video-play').addEventListener('click', playWithSound);
    document.getElementById('intro-video-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        const bounds = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right
            || event.clientY < bounds.top || event.clientY > bounds.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => {
        video.pause();
        prompt.hidden = true;
        document.body.style.overflow = previousOverflow;
        document.documentElement.style.overflow = previousRootOverflow;
        if (previousFocus instanceof HTMLElement) previousFocus.focus({ preventScroll: true });
    });
    video.addEventListener('playing', () => {
        if (!dialog.open) video.pause();
        prompt.hidden = true;
        error.hidden = true;
    });
    video.addEventListener('error', () => {
        prompt.hidden = true;
        error.hidden = false;
    });
    window.addEventListener('pagehide', () => video.pause());
    window.addEventListener('pageshow', (event) => {
        if (!event.persisted) return;
        if (dialog.open) {
            video.currentTime = 0;
            playWithSound();
        } else {
            openVideo();
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openVideo, { once: true });
    } else {
        openVideo();
    }
})();
</script>
@endpush
