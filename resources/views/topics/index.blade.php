<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Page Header -->
        <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 sm:p-7 shadow-xs overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="max-w-xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs mb-3 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-correct"></span>
                        <span>Official ITPEC Syllabus</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-ink tracking-tight mb-1">Review by topic</h1>
                    <p class="text-xs sm:text-sm text-stone">
                        Browse examination questions organized by topic to strengthen your understanding in specific areas.
                    </p>
                </div>
                <div class="hidden sm:flex shrink-0 items-center justify-center">
                    <x-mascot variant="default" :size="96" label="Passy syllabus guide" />
                </div>
            </div>
        </div>

        <!-- Topics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($topics as $topic)
                <div class="bg-surface rounded-2xl border border-[#CFCABD] p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:border-accent/40 transition-all">
                    <div>
                        <!-- Topic Info -->
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-ink mb-1">{{ $topic['name'] }}</h3>
                                @if($topic['code'])
                                    <span class="inline-block px-2.5 py-0.5 bg-accent-tint border border-accent/20 text-accent text-xs font-mono font-bold rounded-md">
                                        {{ $topic['code'] }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        @if($topic['description'])
                            <p class="text-xs sm:text-sm text-stone mb-4 line-clamp-2 leading-relaxed">
                                {{ $topic['description'] }}
                            </p>
                        @endif

                        <!-- Question Count -->
                        <div class="mb-5">
                            <div class="flex items-center text-xs font-mono text-stone bg-paper px-3 py-1.5 rounded-lg border border-[#E3DFD5]">
                                <svg class="w-4 h-4 mr-1.5 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-bold text-ink mr-1">{{ number_format($topic['published_questions']) }}</span>
                                <span>questions available</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2 pt-4 border-t border-[#E3DFD5]">
                        <a 
                            href="{{ route('topics.show', $topic['id']) }}"
                            class="block w-full text-center px-4 py-2.5 bg-accent text-surface rounded-xl hover:bg-accent/90 transition-all text-xs font-semibold shadow-xs"
                        >
                            Review questions
                        </a>
                        
                        <a 
                            href="{{ route('topics.practice', $topic['id']) }}"
                            class="block w-full text-center px-4 py-2.5 border border-[#D9D5C9] bg-surface text-accent rounded-xl hover:bg-paper transition-all text-xs font-medium shadow-xs hover:border-accent/40"
                        >
                            Practice this topic &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if(count($topics) === 0)
            <div class="text-center py-12 bg-surface rounded-2xl border border-[#CFCABD] p-8 shadow-xs">
                <svg class="mx-auto h-12 w-12 text-stone/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-base font-bold text-ink">No topics available</h3>
                <p class="mt-1 text-xs text-stone">Topics will appear here once content is published.</p>
            </div>
        @endif
    </div>
</x-app-layout>

