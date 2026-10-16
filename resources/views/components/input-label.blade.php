@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-medium text-light small mb-1']) }}>
    {{ $value ?? $slot }}
</label>
