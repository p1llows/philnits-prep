<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div>
                <h1 class="text-2xl font-semibold text-ink mb-1">Assessment results</h1>
                <p class="text-sm text-stone">Review your performance summary and topic breakdown.</p>
            </div>

            <!-- Score Card -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden p-6 md:p-8">
                <div class="text-center mb-8">
                    <h3 class="text-xs font-medium text-stone mb-2">Your score</h3>
                    
                    @php
                        $score = $assessment->score_percentage ?? 0;
                        $isAboveBenchmark = $score >= 60;
                    @endphp
                    
                    <div class="flex items-baseline justify-center">
                        <span class="text-6xl md:text-7xl font-semibold text-accent">{{ number_format($score, 1) }}</span>
                        <span class="text-xl text-stone ml-1.5">%</span>
                    </div>
                    
                    <div class="mt-4 flex justify-center items-center">
                        @if($isAboveBenchmark)
                            <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-correct-surface text-correct border border-line">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Above preparation benchmark
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded text-xs font-medium bg-wrong-surface text-wrong border border-line">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Below preparation benchmark
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Summary Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-correct-surface border border-line rounded-lg">
                        <div class="text-2xl font-semibold text-correct">{{ $assessment->correct_count }}</div>
                        <div class="text-xs text-correct mt-0.5">Correct</div>
                    </div>
                    
                    <div class="text-center p-4 bg-wrong-surface border border-line rounded-lg">
                        <div class="text-2xl font-semibold text-wrong">{{ $assessment->incorrect_count }}</div>
                        <div class="text-xs text-wrong mt-0.5">Incorrect</div>
                    </div>
                    
                    <div class="text-center p-4 bg-paper border border-line rounded-lg">
                        <div class="text-2xl font-semibold text-ink">{{ $assessment->total_questions }}</div>
                        <div class="text-xs text-stone mt-0.5">Total questions</div>
                    </div>
                    
                    <div class="text-center p-4 bg-paper border border-line rounded-lg">
                        <div class="text-2xl font-semibold text-ink">{{ floor($assessment->time_spent_seconds / 60) }}m</div>
                        <div class="text-xs text-stone mt-0.5">Time spent</div>
                    </div>
                </div>

                <!-- Benchmark Comparison -->
                <div class="pt-6 border-t border-line text-center">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-4 h-4 text-stone mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-sm font-medium text-ink">Preparation benchmark</span>
                    </div>
                    <p class="text-xs text-stone mb-2">
                        Your score of <strong class="text-ink font-medium">{{ number_format($score, 1) }}%</strong> is 
                        @if($isAboveBenchmark)
                            <span class="text-correct font-medium">above</span>
                        @else
                            <span class="text-wrong font-medium">below</span>
                        @endif
                        the product's preparation benchmark of <strong class="text-ink font-medium">60%</strong>.
                    </p>
                    <p class="text-xs text-stone/80">
                        Note: This benchmark is for internal preparation tracking and should not be considered a guarantee of official examination results.
                    </p>
                </div>
            </div>

            <!-- Topic Performance -->
            @if(count($topicPerformance) > 0)
                <div class="bg-surface rounded-xl border border-line overflow-hidden p-6 md:p-8">
                    <h3 class="text-base font-medium text-ink mb-6">Topic performance breakdown</h3>
                    
                    <div class="space-y-4">
                        @foreach($topicPerformance as $perf)
                            <div class="border border-line bg-paper/30 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-2.5 h-2.5 rounded-full mr-2 bg-accent"></div>
                                        <span class="text-sm font-medium text-ink">{{ $perf['topic_name'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-semibold text-ink">{{ $perf['percentage'] }}%</span>
                                        <span class="text-xs text-stone ml-2">({{ $perf['correct'] }}/{{ $perf['total'] }})</span>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-line rounded-full h-2">
                                    <div 
                                        class="h-2 rounded-full transition-all duration-300 bg-accent" 
                                        style="width: {{ $perf['percentage'] }}%"
                                    ></div>
                                </div>
                                
                                @if($perf['percentage'] < 50)
                                    <div class="mt-2 text-xs text-wrong font-medium">
                                        This topic needs improvement
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Weak Areas & Recommendations -->
            @if($weakAreas->count() > 0)
                <div class="bg-surface rounded-xl border border-line overflow-hidden p-6 md:p-8">
                    <h3 class="text-base font-medium text-ink mb-2">Focus areas</h3>
                    <p class="text-xs text-stone mb-6">
                        Based on your performance, these topics are showing weaker understanding. We recommend reviewing these areas first.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($weakAreas as $area)
                            <div class="border border-line bg-paper p-4 rounded-lg flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-sm text-ink">{{ $area['topic_name'] }}</h4>
                                    <p class="text-xs text-stone mt-1">
                                        {{ $area['correct'] }} of {{ $area['total'] }} correct ({{ $area['percentage'] }}%)
                                    </p>
                                </div>
                                <a 
                                    href="/topics/{{ $area['topic_id'] }}"
                                    class="px-3 py-1.5 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors shrink-0"
                                >
                                    Review now
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recommended Actions -->
            @if($recommendedTopics->count() > 0)
                <div class="bg-surface rounded-xl border border-line overflow-hidden p-6 md:p-8">
                    <h3 class="text-base font-medium text-ink mb-4">Recommended next steps</h3>
                    
                    <div class="space-y-3">
                        @foreach($recommendedTopics as $idx => $topic)
                            <div class="flex items-center justify-between p-4 border border-line bg-paper/30 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-7 h-7 bg-paper border border-line text-ink rounded-full flex items-center justify-center font-bold text-xs mr-3">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm text-ink">{{ $topic['topic_name'] }}</p>
                                        <p class="text-xs text-stone">Practice questions in this topic</p>
                                    </div>
                                </div>
                                <a 
                                    href="/topics/{{ $topic['topic_id'] }}"
                                    class="px-4 py-2 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors"
                                >
                                    Practice →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4 pt-4">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 border border-line bg-surface text-ink rounded-lg text-xs font-medium hover:bg-paper transition-colors">
                    ← Back to dashboard
                </a>
                
                <a href="{{ route('assessment.index') }}" 
                   class="px-5 py-2.5 bg-ink text-paper rounded-lg text-xs font-medium hover:bg-black transition-colors">
                    Take another assessment →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

