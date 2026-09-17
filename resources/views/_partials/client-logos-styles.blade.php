{{--
    Klien Aldef Tech — band, tile and grid.

    Shared by the homepage marquee and the About grid so the same logo is never
    styled two different ways: both pages render one component and include this
    one block. Only the marquee motion itself lives with the homepage, since
    that is the only place it is used.
--}}
<style>
    /* ── Band ──────────────────────────────────────────────────────────────── */
    .client-band {
        isolation: isolate;
        color: #e8eaee;
        background:
            radial-gradient(66% 92% at 2% 0%, rgb(195 30 48 / 13%) 0%, transparent 62%),
            radial-gradient(72% 100% at 100% 100%, rgb(123 12 24 / 19%) 0%, transparent 66%),
            linear-gradient(168deg, #050506 0%, #090708 46%, #150609 100%);
        border-block: 1px solid rgb(255 255 255 / 7%);
    }

    /* A single soft bloom; the brief calls for ambience, not glow. */
    .client-band-glow {
        position: absolute;
        z-index: -1;
        width: 26rem;
        height: 26rem;
        right: -14rem;
        top: -12rem;
        border-radius: 999px;
        filter: blur(90px);
        background: rgb(159 13 31 / 17%);
        pointer-events: none;
    }

    .client-band-eyebrow { color: #ffb1b9; }
    .client-band-eyebrow::before { background: linear-gradient(90deg, transparent, #e23c4f); }

    .client-band-accent {
        background: linear-gradient(105deg, #f3d8c7 0%, #e8d3a7 45%, #df3a4d 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
    }

    /* ── Logo tile ─────────────────────────────────────────────────────────── */
    .client-logo {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 11.5rem;
        height: 5rem;
        padding: 0.875rem 1.25rem;
        border-radius: 0.875rem;
        border: 1px solid rgb(255 255 255 / 9%);
        background:
            radial-gradient(125% 140% at 100% 0%, rgb(139 17 34 / 15%) 0%, transparent 62%),
            linear-gradient(152deg, rgb(255 255 255 / 4.5%) 0%, rgb(255 255 255 / 1.5%) 100%);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 4%);
        isolation: isolate;
        overflow: hidden;
        transition:
            transform 560ms var(--e-soft),
            border-color 420ms var(--e-glide),
            background 420ms var(--e-glide),
            box-shadow 560ms var(--e-soft);
    }

    /* Gold micro-hairline on the top edge, as on the other dark cards. */
    .client-logo::after {
        content: '';
        position: absolute;
        top: 0;
        left: 18%;
        right: 18%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgb(232 211 167 / 70%), transparent);
        opacity: 0;
        transition: opacity 420ms var(--e-glide);
        pointer-events: none;
    }

    /* Contain, never crop: client marks arrive in every aspect ratio. Capped at
       its natural size so a small logo is never scaled up and blurred. */
    .client-logo img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        filter: grayscale(100%) opacity(.58);
        transition: filter 480ms var(--e-glide), transform 560ms var(--e-soft);
    }

    @media (hover: hover) {
        .client-logo:hover {
            transform: translateY(-2px);
            border-color: rgb(232 211 167 / 26%);
            background:
                radial-gradient(125% 140% at 100% 0%, rgb(155 20 39 / 26%) 0%, transparent 64%),
                linear-gradient(152deg, rgb(255 255 255 / 6.5%) 0%, rgb(255 255 255 / 2.5%) 100%);
            box-shadow: inset 0 1px 0 rgb(255 255 255 / 6%), 0 18px 34px -26px rgb(89 5 18 / 70%);
        }
        .client-logo:hover img {
            filter: grayscale(0) opacity(1);
            transform: scale(1.045);
        }
        .client-logo:hover::after { opacity: 1; }
    }

    /* Keyboard parity with the hover state, since a linked logo is a control. */
    .client-logo:focus-visible {
        outline: 2px solid #e8d3a7;
        outline-offset: 3px;
    }

    /* ── About grid ────────────────────────────────────────────────────────── */
    .client-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.875rem;
    }

    @media (min-width: 640px) {
        .client-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
    }

    @media (min-width: 1024px) {
        .client-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }

    @media (min-width: 1280px) {
        .client-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
    }

    /* In the grid the tile fills its column instead of holding a fixed width. */
    .client-grid .client-logo {
        width: 100%;
        height: 5.5rem;
        min-width: 0;
    }

    @media (max-width: 639px) {
        .client-grid .client-logo { height: 4.75rem; padding: 0.75rem; }
    }
</style>
