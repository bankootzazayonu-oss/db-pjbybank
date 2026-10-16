<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger fw-semibold px-4 py-2']) }}>
    {{ $slot }}
</button>
