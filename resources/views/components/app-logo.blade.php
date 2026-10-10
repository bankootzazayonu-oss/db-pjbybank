@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand :name="''" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto rounded-xl object-contain shadow-md" alt="CineReview Logo" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="''" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto rounded-xl object-contain shadow-md" alt="CineReview Logo" />
        </x-slot>
    </flux:brand>
@endif
