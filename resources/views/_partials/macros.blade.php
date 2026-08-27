@php
  // Aldef Tech brand mark. The logo artwork carries its own colour gradient, so
  // it is used as an image rather than a currentColor SVG.
  $height = $height ?? '30';
@endphp

<img src="{{ asset('images/logo-square.png') }}"
     alt="{{ config('variables.templateName', 'Aldef Tech') }}"
     height="{{ $height }}"
     style="height: {{ $height }}px; width: auto; display: block;">
