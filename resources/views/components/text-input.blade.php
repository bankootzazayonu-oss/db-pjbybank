@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-control bg-dark text-light border-secondary']) }}>
