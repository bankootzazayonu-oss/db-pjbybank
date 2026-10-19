@props([
    'sidebar' => false,
])

<a {{ $attributes->merge(['class' => 'd-flex align-items-center text-decoration-none']) }}>
    <img src="{{ asset('images/logo.png') }}" class="rounded shadow" style="height: {{ $sidebar ? '40px' : '48px' }}; width: auto; object-fit: contain;" alt="CineReview Logo" />
</a>
