@props(['for', 'value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value }}
    @if($for)
        <span class="text-red-500">*</span>
    @endif
</label>
