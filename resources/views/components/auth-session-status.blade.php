@props(['status', 'type' => 'success'])

@if ($status)
    <div {{ $attributes->merge(['class' => "mb-4 font-medium text-sm p-3 rounded-lg border border-line bg-correct-surface text-correct"]) }}>
        {{ __($status) }}
    </div>
@endif

