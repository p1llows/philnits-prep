@props([
    'variant' => 'default',
    'size' => 120,
    'label' => null,
])

@php
    $files = [
        'default' => 'paxi.svg',
        'wave' => 'paxi.svg',
        'think' => 'paxi-think.svg',
        'cheer' => 'paxi-cheer.svg',
        'sleepy' => 'paxi-sleepy.svg',
        'timer' => 'paxi-timer.svg',
        'inspect' => 'paxi-inspect.svg',
        'passed' => 'paxi-passed.svg',
        'oops' => 'paxi-oops.svg',
        'avatar' => 'paxi-avatar.svg',
    ];
    $filename = $files[$variant] ?? 'paxi.svg';
@endphp

<img
    src="{{ asset('mascot/' . $filename) }}"
    width="{{ $size }}"
    height="{{ $size }}"
    alt="{{ $label ?? '' }}"
    @if(empty($label)) aria-hidden="true" @endif
    {{ $attributes->merge(['class' => 'inline-block select-none']) }}
/>
