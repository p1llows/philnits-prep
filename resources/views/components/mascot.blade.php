@props([
    'variant' => 'default',
    'size' => 120,
    'label' => null,
])

@php
    $files = [
        'default' => 'passy.svg',
        'avatar' => 'passy-avatar.svg',
        'passed' => 'passy-passed.svg',
        'oops' => 'passy-oops.svg',
    ];
    $filename = $files[$variant] ?? 'passy.svg';
@endphp

<img
    src="{{ asset('mascot/' . $filename) }}"
    width="{{ $size }}"
    height="{{ $size }}"
    alt="{{ $label ?? 'Paxi mascot' }}"
    @if(empty($label)) aria-hidden="true" @endif
    {{ $attributes->merge(['class' => 'inline-block select-none']) }}
/>
