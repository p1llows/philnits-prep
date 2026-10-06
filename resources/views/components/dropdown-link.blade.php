@props(['href' => '#', 'active' => false])

@php
$classes = $active
    ? 'block w-full px-4 py-2 text-start text-sm leading-5 text-white transition duration-150 ease-in-out bg-primary-600 focus:outline-none focus:bg-primary-700 focus:text-white'
    : 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-800';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} href="{{ $href }}">
    {{ $slot }}
</a>
