<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8" x-data="{ search: '', selectedField: 'all' }">
        
        <!-- Header & Mascot Banner (Clean Single-Card Layout, No Nested Cards) -->
        <div class="relative bg-surface rounded-3xl border border-[#D9D5C9] p-6 sm:p-8 md:p-10 shadow-xs overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 sm:gap-8">
                <!-- Left Content: Title, description, and search -->
                <div class="space-y-3 max-w-xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs font-medium shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                        <span>IT PASSPORT SYLLABUS</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ink tracking-tight">Study Topics</h1>
                    <p class="text-xs sm:text-sm text-stone leading-relaxed">
                        Select a knowledge domain to begin focused practice. Each topic contains verified IT Passport exam questions with full explanations.
                    </p>
                    
                    <!-- Search Input Bar integrated directly -->
                    <div class="relative max-w-md pt-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search"
                            placeholder="Search topics by keyword..." 
                            class="w-full pl-10 pr-9 py-2.5 bg-paper border border-[#D9D5C9] rounded-xl text-xs sm:text-sm text-ink placeholder-stone/70 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition-all shadow-xs"
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
                </div>

                <!-- Right Side: Big Paxi Cheer Mascot directly on banner (no nested card) -->
                <div class="hidden sm:flex flex-col items-center justify-center shrink-0 text-center space-y-2">
                    <x-mascot variant="cheer" :size="130" class="w-28 h-28 sm:w-32 sm:h-32 drop-shadow-md" />
                    <div class="text-[11px] font-mono font-bold text-accent px-3 py-1 rounded-full bg-paper border border-[#D9D5C9] shadow-xs">
                        {{ count($topics) }} TOPICS AVAILABLE
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Selector Tabs: All, Strategy, Management, Technology -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1">
            <button 
                @click="selectedField = 'all'" 
                :class="selectedField === 'all' ? 'bg-accent text-surface shadow-xs' : 'bg-surface text-stone hover:text-ink border border-[#E3DFD5]'"
                class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0">
                All Domains
            </button>
            <button 
                @click="selectedField = 'strategy'" 
                :class="selectedField === 'strategy' ? 'bg-accent text-surface shadow-xs' : 'bg-surface text-stone hover:text-ink border border-[#E3DFD5]'"
                class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 inline-flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-[#CA8A04]"></span>
                <span>Strategy (Field 01 · ~35%)</span>
            </button>
            <button 
                @click="selectedField = 'management'" 
                :class="selectedField === 'management' ? 'bg-accent text-surface shadow-xs' : 'bg-surface text-stone hover:text-ink border border-[#E3DFD5]'"
                class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 inline-flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                <span>Management (Field 02 · ~25%)</span>
            </button>
            <button 
                @click="selectedField = 'technology'" 
                :class="selectedField === 'technology' ? 'bg-accent text-surface shadow-xs' : 'bg-surface text-stone hover:text-ink border border-[#E3DFD5]'"
                class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 inline-flex items-center space-x-1.5">
                <span class="w-2 h-2 rounded-full bg-[#3B82F6]"></span>
                <span>Technology (Field 03 · ~40%)</span>
            </button>
        </div>

        <!-- Topics Grid (grouped and searchable) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
            @foreach($topics as $topic)
                @php
                    $nameLower = strtolower($topic['name']);
                    $fieldKey = strtolower($topic['field'] ?? 'technology');
                    // Match icons by topic keywords
                    $iconType = 'computer';
                    if (str_contains($nameLower, 'network') || str_contains($nameLower, 'telecom')) {
                        $iconType = 'network';
                    } elseif (str_contains($nameLower, 'security') || str_contains($nameLower, 'defense') || str_contains($nameLower, 'audit')) {
                        $iconType = 'security';
                    } elseif (str_contains($nameLower, 'software') || str_contains($nameLower, 'program') || str_contains($nameLower, 'algorithm') || str_contains($nameLower, 'pseudo')) {
                        $iconType = 'code';
                    } elseif (str_contains($nameLower, 'database') || str_contains($nameLower, 'data')) {
                        $iconType = 'database';
                    } elseif (str_contains($nameLower, 'strategy') || str_contains($nameLower, 'business') || str_contains($nameLower, 'corporate') || str_contains($nameLower, 'finance') || str_contains($nameLower, 'accounting') || str_contains($nameLower, 'legal')) {
                        $iconType = 'briefcase';
                    }
                @endphp

                <div 
                    x-show="(selectedField === 'all' || selectedField === '{{ $fieldKey }}') && (search === '' || '{{ strtolower(addslashes($topic['name'])) }}'.includes(search.toLowerCase()) || '{{ strtolower(addslashes($topic['description'] ?? '')) }}'.includes(search.toLowerCase()))"
                    class="group bg-surface rounded-2xl border border-[#E3DFD5] p-5 sm:p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:border-accent/40 transition-all duration-200 space-y-4"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start space-x-3.5 min-w-0">
                            <!-- Field Icon Tile -->
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-accent text-surface flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform duration-200 mt-0.5">
                                @if($iconType === 'network')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                @elseif($iconType === 'security')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                @elseif($iconType === 'code')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                @elseif($iconType === 'database')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                    </svg>
                                @elseif($iconType === 'briefcase')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>

                            <!-- Topic Info -->
                            <div class="min-w-0 space-y-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider
                                        {{ $fieldKey === 'strategy' ? 'bg-[#CA8A04]/15 text-[#854D0E]' : ($fieldKey === 'management' ? 'bg-[#10B981]/15 text-[#047857]' : 'bg-[#3B82F6]/15 text-[#1D4ED8]') }}">
                                        {{ $topic['field_label'] ?? ucfirst($fieldKey) }}
                                    </span>
                                    <span class="text-[11px] font-mono text-stone">
                                        {{ number_format($topic['published_questions']) }} questions
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-ink leading-snug group-hover:text-accent transition-colors">
                                    {{ $topic['name'] }}
                                </h3>
                                @if(!empty($topic['description']))
                                    <p class="text-xs text-stone line-clamp-2 leading-relaxed">
                                        {{ $topic['description'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions Bottom Row -->
                    <div class="pt-2 border-t border-[#E3DFD5]/60 flex items-center justify-between">
                        <span class="text-[11px] font-mono text-stone">Code: {{ $topic['code'] }}</span>
                        <div class="flex items-center space-x-2">
                            <a 
                                href="{{ route('topics.practice', $topic['id']) }}"
                                class="inline-flex items-center px-3.5 py-1.5 bg-paper hover:bg-accent hover:text-surface text-accent text-xs font-semibold rounded-lg border border-[#E3DFD5] transition-colors shadow-xs"
                            >
                                Practice
                            </a>
                            <a 
                                href="{{ route('topics.show', $topic['id']) }}"
                                class="w-8 h-8 rounded-lg bg-paper hover:bg-accent hover:text-surface text-stone flex items-center justify-center transition-all border border-[#E3DFD5] group-hover:border-accent"
                                aria-label="View {{ $topic['name'] }} questions"
                            >
                                <svg class="w-3.5 h-3.5 text-stone group-hover:text-surface transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
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
