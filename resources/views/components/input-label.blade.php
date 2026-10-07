@props(['for', 'value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-ink']) }}>
    {{ $value }}
    @if($for)
        <span class="text-wrong">*</span>
    @endif
</label>

