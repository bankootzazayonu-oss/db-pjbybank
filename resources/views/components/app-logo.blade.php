@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="''" {{ $attributes }}>
        <x-slot name="logo" class="d-flex align-items-center justify-content-center">
            <img src="{{ asset('images/logo.png') }}" class="rounded shadow" style="height: 40px; width: auto; object-fit: contain;" alt="CineReview Logo" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="''" {{ $attributes }}>
        <x-slot name="logo" class="d-flex align-items-center justify-content-center">
            <img src="{{ asset('images/logo.png') }}" class="rounded shadow" style="height: 48px; width: auto; object-fit: contain;" alt="CineReview Logo" />
        </x-slot>
    </flux:brand>
@endif
