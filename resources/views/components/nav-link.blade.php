@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold bg-indigo-500/15 text-indigo-400 border border-indigo-500/30 transition duration-150'
            : 'inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-100 hover:bg-slate-800/60 border border-transparent transition duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
