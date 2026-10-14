@props(['text'])

@php
    // Detect markdown-style fenced code blocks ```...``` or pseudocode blocks
    $hasCode = str_contains($text, '```');
@endphp

@if($hasCode)
    <div class="space-y-3 font-sans">
        @php
            $parts = explode('```', $text);
        @endphp
        @foreach($parts as $index => $part)
            @if($index % 2 === 1)
                {{-- Code block --}}
                @php
                    // Strip optional language identifier like "text" or "pseudocode" from first line
                    $lines = explode("\n", ltrim($part));
                    if (isset($lines[0]) && in_array(trim($lines[0]), ['text', 'pseudo', 'pseudocode', 'php', 'code'])) {
                        array_shift($lines);
                    }
                    $cleanCode = implode("\n", $lines);
                @endphp
                <div class="rounded-xl border border-[#D9D5C9] bg-panel p-4 my-3 font-mono text-xs sm:text-sm text-ink overflow-x-auto shadow-inner leading-relaxed">
                    <div class="text-[10px] font-mono uppercase tracking-wider text-stone mb-2 pb-1 border-b border-[#E3DFD5]">
                        ITPEC Pseudoprogramming Language (April 2024+)
                    </div>
                    <pre class="font-mono whitespace-pre text-ink selection:bg-accent-tint">{{ trim($cleanCode) }}</pre>
                </div>
            @else
                @if(trim($part))
                    <p class="whitespace-pre-line leading-relaxed">{{ trim($part) }}</p>
                @endif
            @endif
        @endforeach
    </div>
@else
    <p class="whitespace-pre-line leading-relaxed font-sans">{{ $text }}</p>
@endif
