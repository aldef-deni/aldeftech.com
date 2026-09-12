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

<dialog id="intro-video-dialog" aria-labelledby="intro-video-title" aria-describedby="intro-video-hint">
    <header class="intro-video-header">
        <div>
            <p class="intro-video-eyebrow">{{ __('video.eyebrow') }}</p>
            <h2 id="intro-video-title">{{ $title }}</h2>
        </div>
        <button type="button" id="intro-video-close" autofocus aria-label="{{ __('video.close') }}">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                <path d="m6 6 12 12M18 6 6 18" />
            </svg>
        </button>
    </header>
    <div class="intro-video-stage">
        <video id="intro-video-player" src="{{ $src }}" poster="{{ $poster }}"
               controls playsinline preload="metadata" aria-label="{{ $title }}">
            <a href="{{ $src }}">{{ __('video.download') }}</a>
        </video>
        <div id="intro-video-play-prompt" hidden>
            <button type="button" id="intro-video-play">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <span>{{ __('video.play') }}</span>
            </button>
        </div>
    </div>
    <footer class="intro-video-footer">
        <p id="intro-video-hint">{{ __('video.hint') }}</p>
        <p id="intro-video-error" role="status" hidden>
            {{ __('video.error') }} <a href="{{ $src }}">{{ __('video.download') }}</a>
        </p>
    </footer>
</dialog>

@push('styles')
<style>
    .intro-video-launch { margin-top: 1.5rem; text-align: center; }
    #intro-video-dialog {
        box-sizing: border-box; width: min(58rem, calc(100% - 2rem)); max-width: none;
        max-height: calc(100vh - 2rem); max-height: calc(100dvh - 2rem);
        margin: auto; padding: 0; border: 1px solid rgb(198 166 104 / 45%); border-radius: 1.5rem;
        background: #151719; color: #fff; overflow: auto; overscroll-behavior: contain;
        box-shadow: 0 32px 100px rgb(0 0 0 / 55%), 0 0 0 5px rgb(255 255 255 / 3%);
    }
    #intro-video-dialog::backdrop { background: rgb(8 10 13 / 82%); backdrop-filter: blur(8px); }
    .intro-video-header {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        padding: 1.5rem; border-bottom: 1px solid rgb(198 166 104 / 20%);
        background: linear-gradient(115deg, rgb(198 166 104 / 12%), transparent 70%);
    }
    .intro-video-header > div { min-width: 0; }
    .intro-video-eyebrow { margin: 0 0 .5rem; font-size: .625rem; letter-spacing: .2em; color: #d6b77b; }
    #intro-video-title { margin: 0; font-size: clamp(1rem, 2.5vw, 1.5rem); line-height: 1.4; color: #fff; overflow-wrap: anywhere; }
    #intro-video-close {
        display: grid; place-items: center; flex-shrink: 0; width: 44px; height: 44px;
        border: 1px solid rgb(224 197 142 / 30%); border-radius: 50%;
        background: rgb(255 255 255 / 4%); color: #fff; cursor: pointer;
    }
    #intro-video-close:hover { background: rgb(224 197 142 / 18%); }
    #intro-video-close:focus-visible, #intro-video-play:focus-visible {
        outline: 2px solid #e0c58e; outline-offset: 4px;
    }
    .intro-video-stage {
        position: relative; width: 100%; aspect-ratio: 16 / 9; background: #08090a;
        max-height: calc(100vh - 14rem); max-height: calc(100dvh - 14rem);
    }
    #intro-video-player { display: block; width: 100%; height: 100%; object-fit: contain; }
    #intro-video-play-prompt:not([hidden]) {
        position: absolute; inset: 0 0 3rem; display: flex; align-items: center; justify-content: center;
        padding: 1rem; background: linear-gradient(transparent, rgb(0 0 0 / 35%)); pointer-events: none;
    }
    #intro-video-play {
        display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
        min-height: 48px; padding: .8rem 1.3rem; border: 1px solid #efd9ad; border-radius: 100px;
        background: linear-gradient(135deg, #eed7a7, #c6a368); color: #191714;
        font: inherit; font-size: .875rem; font-weight: 600; cursor: pointer; pointer-events: auto;
        box-shadow: 0 8px 32px rgb(0 0 0 / 35%);
    }
    #intro-video-play:hover { filter: brightness(1.08); }
    .intro-video-footer { padding: 1rem 1.5rem; border-top: 1px solid rgb(198 166 104 / 15%); }
    #intro-video-hint, #intro-video-error { margin: 0; font-size: .8125rem; line-height: 1.6; color: #c9c1b1; }
    #intro-video-error { margin-top: .5rem; }
    #intro-video-error a { color: #eed7a7; text-decoration: underline; }
    @media (max-width: 640px) {
        #intro-video-dialog { width: calc(100% - 1.25rem); border-radius: 1.1rem; }
        .intro-video-header { padding: 1rem; gap: .75rem; }
        .intro-video-eyebrow { font-size: .5625rem; letter-spacing: .14em; }
        .intro-video-footer { padding: .875rem 1rem; }
        #intro-video-play { padding: .65rem 1rem; font-size: .8125rem; }
        .intro-video-launch .btn { max-width: 100%; white-space: normal; }
    }
    @media (max-height: 500px) and (orientation: landscape) {
        #intro-video-dialog { width: min(44rem, calc(100% - 2rem)); }
        .intro-video-header { padding: .625rem 1rem; }
        .intro-video-eyebrow { display: none; }
        .intro-video-stage { max-height: calc(100vh - 10rem); max-height: calc(100dvh - 10rem); }
        .intro-video-footer { padding: .5rem 1rem; }
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
