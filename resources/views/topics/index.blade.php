<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8" x-data="{ search: '' }">
        
        <!-- Header & Mascot Contextual Guidance -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight mb-1">Study Topics</h1>
                <p class="text-xs sm:text-sm text-stone">
                    Choose a topic to start learning
                </p>
            </div>

            <!-- Passy Contextual Guidance Speech Bubble (matching mockup) -->
            <div class="flex items-center space-x-3 bg-surface border border-[#E3DFD5] rounded-2xl p-3 sm:px-4 sm:py-3 shadow-xs shrink-0 max-w-sm">
                <x-mascot variant="avatar" :size="44" class="w-11 h-11 rounded-full shrink-0 shadow-xs" label="Passy avatar" />
                <div class="text-xs">
                    <p class="font-bold text-ink">You got this!</p>
                    <p class="text-stone">Small steps lead to big progress.</p>
                </div>
            </div>
        </div>

        <!-- Search Input Bar (matching mockup) -->
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                type="text" 
                x-model="search"
                placeholder="Search topics..." 
                class="w-full pl-10 pr-4 py-2.5 bg-surface border border-[#E3DFD5] rounded-xl text-xs sm:text-sm text-ink placeholder-stone/70 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all shadow-xs"
            >
            <button 
                x-show="search.length > 0" 
                @click="search = ''" 
                type="button" 
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone hover:text-ink text-xs"
                aria-label="Clear search"
            >
                ✕
            </button>
        </div>

        <!-- Topics Grid (matching mockup cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            @foreach($topics as $topic)
                @php
                    $nameLower = strtolower($topic['name']);
                    // Match icons by topic keywords
                    $iconType = 'computer';
                    if (str_contains($nameLower, 'network') || str_contains($nameLower, 'telecom')) {
                        $iconType = 'network';
                    } elseif (str_contains($nameLower, 'security') || str_contains($nameLower, 'audit')) {
                        $iconType = 'security';
                    } elseif (str_contains($nameLower, 'software') || str_contains($nameLower, 'program') || str_contains($nameLower, 'algorithm')) {
                        $iconType = 'code';
                    } elseif (str_contains($nameLower, 'database') || str_contains($nameLower, 'data')) {
                        $iconType = 'database';
                    } elseif (str_contains($nameLower, 'strategy') || str_contains($nameLower, 'business') || str_contains($nameLower, 'manage')) {
                        $iconType = 'briefcase';
                    }
                @endphp

                <div 
                    x-show="search === '' || '{{ strtolower(addslashes($topic['name'])) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($topic['description'] ?? '')) }}'.includes(search.toLowerCase())"
                    class="group bg-surface rounded-2xl border border-[#E3DFD5] p-5 sm:p-6 flex items-center justify-between shadow-xs hover:shadow-md hover:border-accent/40 transition-all duration-200"
                >
                    <div class="flex items-center space-x-4 min-w-0 pr-4">
                        <!-- Dark Navy Icon Tile (mockup style) -->
                        <div class="w-12 h-12 rounded-xl bg-accent text-surface flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200">
                            @if($iconType === 'network')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            @elseif($iconType === 'security')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            @elseif($iconType === 'code')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            @elseif($iconType === 'database')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            @elseif($iconType === 'briefcase')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>

                        <!-- Topic Info -->
                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-ink truncate group-hover:text-accent transition-colors">
                                {{ $topic['name'] }}
                            </h3>
                            <p class="text-xs text-stone font-mono mt-0.5">
                                {{ number_format($topic['published_questions']) }} questions
                            </p>
                        </div>
                    </div>

                    <!-- Actions / Chevron Arrow -->
                    <div class="flex items-center space-x-2 shrink-0">
                        <a 
                            href="{{ route('topics.practice', $topic['id']) }}"
                            class="hidden sm:inline-flex items-center px-3 py-1.5 bg-paper hover:bg-accent hover:text-surface text-accent text-xs font-semibold rounded-lg border border-[#E3DFD5] transition-colors shadow-xs"
                        >
                            Practice
                        </a>
                        <a 
                            href="{{ route('topics.show', $topic['id']) }}"
                            class="w-9 h-9 rounded-xl bg-paper hover:bg-accent hover:text-surface text-stone flex items-center justify-center transition-all border border-[#E3DFD5] group-hover:border-accent"
                            aria-label="View {{ $topic['name'] }} questions"
                        >
                            <svg class="w-4 h-4 text-stone group-hover:text-surface transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State (matching mockup) -->
        @if(count($topics) === 0)
            <div class="text-center py-12 bg-surface rounded-3xl border border-[#E3DFD5] p-8 shadow-xs space-y-4">
                <div class="flex justify-center">
                    <x-mascot variant="oops" :size="120" />
                </div>
                <h3 class="text-lg font-bold text-ink">No topics available</h3>
                <p class="text-xs sm:text-sm text-stone max-w-sm mx-auto">Topics will appear here once questions are published.</p>
            </div>
        @endif
    </div>
</x-app-layout>
