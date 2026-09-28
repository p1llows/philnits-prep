@props(['status', 'type' => 'success'])

@if ($status)
    <div {{ $attributes->merge(['class' => "mb-4 font-medium text-sm $type"]) }}>
        {{ __($status) }}
    </div>
@endif
