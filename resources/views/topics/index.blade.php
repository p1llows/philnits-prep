<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div>
                <h1 class="text-2xl font-semibold text-ink mb-1">Review by topic</h1>
                <p class="text-sm text-stone">
                    Browse examination questions organized by topic to strengthen your understanding in specific areas.
                </p>
            </div>

            <!-- Topics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($topics as $topic)
                    <div class="bg-surface rounded-xl border border-line p-5 flex flex-col justify-between">
                        <div>
                            <!-- Topic Info -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-base font-medium text-ink mb-1">{{ $topic['name'] }}</h3>
                                    @if($topic['code'])
                                        <span class="inline-block px-2 py-0.5 bg-paper border border-line text-stone text-xs font-medium rounded">
                                            {{ $topic['code'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if($topic['description'])
                                <p class="text-xs text-stone mb-4 line-clamp-2">
                                    {{ $topic['description'] }}
                                </p>
                            @endif

                            <!-- Question Count -->
                            <div class="mb-5">
                                <div class="flex items-center text-xs text-stone">
                                    <svg class="w-4 h-4 mr-1.5 text-stone" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="font-medium text-ink mr-1">{{ number_format($topic['published_questions']) }}</span>
                                    <span>questions available</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-3 border-t border-line">
                            <a 
                                href="{{ route('topics.show', $topic['id']) }}"
                                class="block w-full text-center px-4 py-2 bg-ink text-paper rounded-lg hover:bg-black transition-colors text-xs font-medium"
                            >
                                Review questions
                            </a>
                            
                            <a 
                                href="{{ route('topics.practice', $topic['id']) }}"
                                class="block w-full text-center px-4 py-2 border border-line bg-surface text-ink rounded-lg hover:bg-paper transition-colors text-xs font-medium"
                            >
                                Practice this topic →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty State -->
            @if(count($topics) === 0)
                <div class="text-center py-12 bg-surface rounded-xl border border-line">
                    <svg class="mx-auto h-12 w-12 text-stone" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-ink">No topics available</h3>
                    <p class="mt-1 text-xs text-stone">Topics will appear here once content is published.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

