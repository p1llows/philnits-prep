@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-line bg-surface text-ink focus:border-accent focus:ring-accent rounded-lg text-sm transition duration-150 ease-in-out']) !!}>

