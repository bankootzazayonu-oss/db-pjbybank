@props([
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
])

<?php if ($expandable && $heading): ?>

<ui-disclosure
    {{ $attributes->class('') }}
    @if ($expanded === true) open @endif
    data-flux-navlist-group
>
    <button
        type="button"
        class="d-flex align-items-center w-100 rounded text-secondary btn btn-link text-decoration-none text-start p-2"
    >
        <div class="pe-2">
            <flux:icon.chevron-down class="d-none" />
            <flux:icon.chevron-right class="d-block" />
        </div>

        <span class="small fw-medium">{{ $heading }}</span>
    </button>

    <div class="position-relative ps-3" @if ($expanded === true) data-open @endif>
        {{ $slot }}
    </div>
</ui-disclosure>

<?php elseif ($heading): ?>

<div {{ $attributes->class('d-block mb-2') }}>
    <div class="px-2 py-1">
        <div class="text-uppercase small text-secondary fw-semibold" style="font-size: 0.75rem;">{{ $heading }}</div>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>

<?php else: ?>

<div {{ $attributes->class('d-block') }}>
    {{ $slot }}
</div>

<?php endif; ?>
