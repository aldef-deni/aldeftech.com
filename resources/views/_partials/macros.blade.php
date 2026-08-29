@php
  // Aldef Tech brand mark: the glyph on a transparent ground. The full
  // lock-up (logo-square.png) is 1.3 MB and its wordmark is illegible at this
  // size, so a generated crop of the "A" is used instead.
  $height = $height ?? '30';
@endphp

<img src="{{ asset('images/logo-mark.webp') }}"
     alt="{{ config('variables.templateName', 'Aldef Tech') }}"
     height="{{ $height }}"
     style="height: {{ $height }}px; width: auto; display: block;">
