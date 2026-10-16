@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active fw-bold text-white bg-primary bg-opacity-25 border border-primary border-opacity-50 rounded-3 px-3 py-1.5'
            : 'nav-link text-secondary text-hover-light px-3 py-1.5 rounded-3';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
