@props(['href' => '#', 'active' => false])

@php
$classes = $active
    ? 'block w-full px-4 py-2 text-start text-sm leading-5 text-surface bg-accent font-medium transition duration-150 ease-in-out focus:outline-none'
    : 'block w-full px-4 py-2 text-start text-sm leading-5 text-stone transition duration-150 ease-in-out hover:bg-paper hover:text-ink focus:outline-none focus:bg-paper focus:text-ink';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} href="{{ $href }}">
    {{ $slot }}
</a>

