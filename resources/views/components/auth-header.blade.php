@props([
    'title',
    'description',
])

<div class="d-flex w-100 flex-column text-center mb-3">
    <h3 class="fw-bold text-white mb-1">{{ $title }}</h3>
    <small class="text-secondary">{{ $description }}</small>
</div>
