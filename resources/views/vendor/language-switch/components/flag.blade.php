@props([
    'src',
    'alt' => '',
    'circular' => false,
    'switch' => false,
])
<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    style="
        width: 1.75rem;
        height: 1.75rem;
        object-fit: cover;
        object-position: center;
        display: block;
        flex-shrink: 0;
        {{ $circular ? 'border-radius: 9999px;' : ($switch ? 'border-radius: 6px;' : 'border-radius: 8px;') }}
    "
    {{ $attributes->except(['class', 'style']) }}
/>
