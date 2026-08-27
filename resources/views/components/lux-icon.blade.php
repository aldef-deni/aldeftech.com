@props(['name' => '', 'stroke' => '1.4'])

@php
    /**
     * Refined line icons.
     *
     * Content in the database stores emoji (⚙️, 🤖, …) because that is what the
     * admin editor offers. Emoji render as full-colour bitmaps that break the
     * champagne palette, so we translate them into stroke icons here and fall
     * back to a neutral mark when we meet one we do not know.
     */
    $paths = [
        'gear'        => '<path d="M10.32 4.32a1.72 1.72 0 013.36 0 1.72 1.72 0 002.57 1.06 1.72 1.72 0 012.37 2.37 1.72 1.72 0 001.06 2.57 1.72 1.72 0 010 3.36 1.72 1.72 0 00-1.06 2.57 1.72 1.72 0 01-2.37 2.37 1.72 1.72 0 00-2.57 1.06 1.72 1.72 0 01-3.36 0 1.72 1.72 0 00-2.57-1.06 1.72 1.72 0 01-2.37-2.37 1.72 1.72 0 00-1.06-2.57 1.72 1.72 0 010-3.36 1.72 1.72 0 001.06-2.57 1.72 1.72 0 012.37-2.37 1.72 1.72 0 002.57-1.06z"/><circle cx="12" cy="12" r="2.75"/>',
        'globe'       => '<circle cx="12" cy="12" r="9"/><path d="M3.6 9h16.8M3.6 15h16.8"/><path d="M12 3a15 15 0 010 18 15 15 0 010-18z"/>',
        'cloud'       => '<path d="M7.5 18.5A4.5 4.5 0 016.9 9.6a5.6 5.6 0 0110.8-.9 4.1 4.1 0 01-.7 9.8z"/>',
        'robot'       => '<rect x="4" y="8" width="16" height="12" rx="3"/><path d="M12 4.5V8"/><circle cx="12" cy="3.5" r="1.2"/><path d="M9 13.5h.01M15 13.5h.01M9.5 17h5"/>',
        'bolt'        => '<path d="M13.2 3L5.4 13.2h5.4L10.2 21l7.8-10.2h-5.4L13.2 3z"/>',
        'link'        => '<path d="M10.6 13.4a4 4 0 005.7 0l3-3a4 4 0 10-5.7-5.7l-1.5 1.5"/><path d="M13.4 10.6a4 4 0 00-5.7 0l-3 3a4 4 0 105.7 5.7l1.5-1.5"/>',
        'monitor'     => '<rect x="2.75" y="4" width="18.5" height="12.5" rx="2"/><path d="M8.5 20.5h7M12 16.5v4"/>',
        'chart'       => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
        'box'         => '<path d="M20.5 7.6v8.8a1.6 1.6 0 01-.85 1.42l-6.9 3.75a1.6 1.6 0 01-1.5 0l-6.9-3.75A1.6 1.6 0 013.5 16.4V7.6a1.6 1.6 0 01.85-1.42l6.9-3.75a1.6 1.6 0 011.5 0l6.9 3.75A1.6 1.6 0 0120.5 7.6z"/><path d="M3.8 6.9l8.2 4.4 8.2-4.4M12 21v-9.7"/>',
        'users'       => '<path d="M16.5 20v-1.6a3.6 3.6 0 00-3.6-3.6H7.1a3.6 3.6 0 00-3.6 3.6V20"/><circle cx="10" cy="7.4" r="3.4"/><path d="M20.5 20v-1.6a3.6 3.6 0 00-2.7-3.48M15.6 4.2a3.4 3.4 0 010 6.4"/>',
        'building'    => '<path d="M3.5 20.5h17"/><path d="M5.5 20.5V5.2a1.2 1.2 0 011.2-1.2h6.6a1.2 1.2 0 011.2 1.2v15.3"/><path d="M14.5 20.5V10h3.8a1.2 1.2 0 011.2 1.2v9.3"/><path d="M8.5 8h3M8.5 11.5h3M8.5 15h3"/>',
        'wallet'      => '<path d="M3.5 8.2A2.2 2.2 0 015.7 6h11.6a2.2 2.2 0 012.2 2.2v8.6a2.2 2.2 0 01-2.2 2.2H5.7a2.2 2.2 0 01-2.2-2.2z"/><path d="M3.5 10h17"/><circle cx="16" cy="14.2" r="1.1"/>',
        'sliders'     => '<path d="M5 20V13M5 9V4M12 20v-9M12 7V4M19 20v-5M19 11V4"/><path d="M2.5 13h5M9.5 7h5M16.5 15h5"/>',
        'brain'       => '<path d="M12 5.2a2.7 2.7 0 00-5 1.4A2.6 2.6 0 005 9.1a2.6 2.6 0 001 2.1 2.7 2.7 0 00.6 4.3A2.7 2.7 0 0012 18.8z"/><path d="M12 5.2a2.7 2.7 0 015 1.4 2.6 2.6 0 012 2.5 2.6 2.6 0 01-1 2.1 2.7 2.7 0 01-.6 4.3 2.7 2.7 0 01-5.4 3.3"/><path d="M12 5.2v13.6"/>',
        'wrench'      => '<path d="M14.5 6.2a4.4 4.4 0 015.9 5.6l-1.6-1.6-2.2.6-.6-2.2z"/><path d="M14.9 9.9L4.6 20.2a2 2 0 01-2.8-2.8L12.1 7.1"/>',
        'shield'      => '<path d="M12 3l7.2 2.9v5.3c0 4.4-3 8.2-7.2 9.6-4.2-1.4-7.2-5.2-7.2-9.6V5.9z"/><path d="M9.3 12.2l1.9 1.9 3.6-3.9"/>',
        'compass'     => '<circle cx="12" cy="12" r="9"/><path d="M15.4 8.6l-1.9 5-5 1.9 1.9-5z"/>',
        'search'      => '<circle cx="10.8" cy="10.8" r="6.6"/><path d="M15.6 15.6L20.5 20.5"/>',
        'blueprint'   => '<rect x="3.2" y="4.4" width="17.6" height="15.2" rx="2"/><path d="M3.2 9.6h17.6M8.6 9.6v10M14 4.4v5.2"/>',
        'code'        => '<path d="M8.6 8.2L4.4 12l4.2 3.8M15.4 8.2L19.6 12l-4.2 3.8M13.4 5l-2.8 14"/>',
        'flask'       => '<path d="M9.6 3.5h4.8M10.6 3.5v5.3L5.4 17.4a2 2 0 001.7 3.1h9.8a2 2 0 001.7-3.1l-5.2-8.6V3.5"/><path d="M7.6 14.4h8.8"/>',
        'rocket'      => '<path d="M12 3.2c3 1.9 4.8 5.2 4.8 8.8v3.2H7.2V12c0-3.6 1.8-6.9 4.8-8.8z"/><circle cx="12" cy="10" r="1.7"/><path d="M7.2 15.2L4.6 18l2.6.6.6 2.6 2.4-2.6M16.8 15.2l2.6 2.8-2.6.6-.6 2.6-2.4-2.6"/>',
        'lifebuoy'    => '<circle cx="12" cy="12" r="8.6"/><circle cx="12" cy="12" r="3.6"/><path d="M6 6l3.4 3.4M14.6 14.6L18 18M18 6l-3.4 3.4M9.4 14.6L6 18"/>',
        'spark'       => '<path d="M12 3.4l1.9 5.2 5.2 1.9-5.2 1.9-1.9 5.2-1.9-5.2L4.9 10.5l5.2-1.9z"/><path d="M18.6 16.6l.7 1.9 1.9.7-1.9.7-.7 1.9-.7-1.9-1.9-.7 1.9-.7z"/>',
        'target'      => '<circle cx="12" cy="12" r="8.6"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/>',
        'layers'      => '<path d="M12 3.2l8.4 4.4-8.4 4.4-8.4-4.4z"/><path d="M3.6 12.2l8.4 4.4 8.4-4.4M3.6 16.4l8.4 4.4 8.4-4.4"/>',
        'lock'        => '<rect x="4.6" y="10.4" width="14.8" height="10" rx="2"/><path d="M8.2 10.4V7.6a3.8 3.8 0 017.6 0v2.8"/>',
        'mail'        => '<rect x="3" y="5.2" width="18" height="13.6" rx="2.2"/><path d="M3.6 6.6l7.4 5.2a1.8 1.8 0 002 0l7.4-5.2"/>',
        'phone'       => '<path d="M6.2 3.6h3l1.6 4-2 1.4a12.4 12.4 0 006.2 6.2l1.4-2 4 1.6v3a1.8 1.8 0 01-2 1.8A16.6 16.6 0 014.4 5.6a1.8 1.8 0 011.8-2z"/>',
        'clock'       => '<circle cx="12" cy="12" r="8.6"/><path d="M12 7.2V12l3.2 1.9"/>',
        'dot'         => '<circle cx="12" cy="12" r="7.2"/><circle cx="12" cy="12" r="2.4"/>',
    ];

    $aliases = [
        '⚙️' => 'gear',    '⚙' => 'gear',     '🔧' => 'wrench',  '🛠️' => 'wrench',
        '🌐' => 'globe',   '☁️' => 'cloud',   '☁' => 'cloud',
        '🤖' => 'robot',   '⚡' => 'bolt',    '🔗' => 'link',
        '💻' => 'monitor', '🖥️' => 'monitor', '📊' => 'chart',   '📈' => 'chart',
        '📦' => 'box',     '👥' => 'users',   '🏢' => 'building',
        '💰' => 'wallet',  '🎛️' => 'sliders', '🧠' => 'brain',
        '🔒' => 'lock',    '🔐' => 'lock',    '🛡️' => 'shield',
        '🚀' => 'rocket',  '🎯' => 'target',  '✨' => 'spark',
        '🔍' => 'search',  '🧪' => 'flask',   '📋' => 'blueprint',
        '💡' => 'spark',   '🧭' => 'compass', '📐' => 'blueprint',
        '📱' => 'monitor', '🗂️' => 'layers',  '📧' => 'mail',
        '📞' => 'phone',   '⏱️' => 'clock',   '🤝' => 'users',
    ];

    $key = trim((string) $name);
    $key = $aliases[$key] ?? $key;
    $body = $paths[$key] ?? $paths['dot'];
@endphp

<svg {{ $attributes->merge(['class' => 'w-6 h-6']) }}
     viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="{{ $stroke }}" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">{!! $body !!}</svg>
