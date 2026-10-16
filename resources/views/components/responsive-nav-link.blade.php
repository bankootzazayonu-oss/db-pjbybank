@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active fw-bold text-white bg-primary bg-opacity-25 border-start border-4 border-primary px-3 py-2 rounded-end'
            : 'nav-link text-secondary text-hover-light px-3 py-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
