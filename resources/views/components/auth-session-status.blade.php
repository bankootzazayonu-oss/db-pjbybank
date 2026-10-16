@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success small py-2 px-3 mb-3']) }}>
        {{ $status }}
    </div>
@endif
