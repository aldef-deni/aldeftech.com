@props(['published' => false, 'labels' => ['Terbit', 'Draf']])

<span class="badge {{ $published ? 'bg-label-success' : 'bg-label-secondary' }}">
    {{ $published ? $labels[0] : $labels[1] }}
</span>
