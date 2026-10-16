@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-2 bg-dark border border-secondary shadow-lg rounded-3'])

@php
$alignmentClasses = match ($align) {
    'left' => 'start-0',
    'top' => 'bottom-100 mb-2',
    default => 'end-0',
};
@endphp

<div class="position-relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition
            class="position-absolute z-3 mt-2 {{ $alignmentClasses }} dropdown-menu show bg-dark border-secondary shadow-lg p-1"
            style="display: none; min-width: 13rem;"
            @click="open = false">
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
