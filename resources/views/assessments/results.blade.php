<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ASSESSMENT REPORT</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Assessment results</h1>
                    <p class="text-sm text-stone">Review your performance summary and topic breakdown.</p>
                </div>
                <div class="flex items-center space-x-3 shrink-0">
                    <a href="{{ route('assessment.history') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl text-xs font-medium transition-all shadow-xs">
                        <span>Past attempts</span>
                    </a>
                    <a href="{{ route('assessment.index') }}" class="inline-flex items-center space-x-2 px-5 py-2 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>Retake assessment</span>
                    </a>
                </div>
            </div>

            <!-- Score Card -->
            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden p-6 md:p-8 shadow-xs">
                <div class="text-center mb-8">
                    <h3 class="text-xs font-mono font-medium text-stone uppercase tracking-wider mb-2">Overall Score</h3>
                    
                    @php
                        $score = $assessment->score_percentage ?? 0;
                        $isAboveBenchmark = $score >= 60;
                    @endphp
                    
                    <div class="flex items-baseline justify-center">
                        <span class="text-6xl md:text-7xl font-serif font-bold text-accent">{{ number_format($score, 1) }}</span>
                        <span class="text-2xl font-mono text-stone ml-1.5">%</span>
                    </div>
                    
                    <div class="mt-4 flex justify-center items-center">
                        @if($isAboveBenchmark)
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-mono font-semibold bg-correct-surface/50 text-correct border border-correct/30 shadow-xs">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                ABOVE PREPARATION BENCHMARK (60%)
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-mono font-semibold bg-wrong-surface/50 text-wrong border border-wrong/30 shadow-xs">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                BELOW PREPARATION BENCHMARK (60%)
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Summary Statistics Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-correct-surface/30 border border-correct/30 rounded-xl">
                        <div class="text-2xl font-mono font-bold text-correct">{{ $assessment->correct_count }}</div>
                        <div class="text-xs font-mono text-correct mt-1">Correct answers</div>
                    </div>
                    
                    <div class="text-center p-4 bg-wrong-surface/30 border border-wrong/30 rounded-xl">
                        <div class="text-2xl font-mono font-bold text-wrong">{{ $assessment->incorrect_count }}</div>
                        <div class="text-xs font-mono text-wrong mt-1">Incorrect answers</div>
                    </div>
                    
                    <div class="text-center p-4 bg-paper/70 border border-[#E3DFD5] rounded-xl">
                        <div class="text-2xl font-mono font-bold text-ink">{{ $assessment->total_questions }}</div>
                        <div class="text-xs font-mono text-stone mt-1">Total questions</div>
                    </div>
                    
                    <div class="text-center p-4 bg-paper/70 border border-[#E3DFD5] rounded-xl">
                        <div class="text-2xl font-mono font-bold text-ink">{{ floor($assessment->time_spent_seconds / 60) }}m</div>
                        <div class="text-xs font-mono text-stone mt-1">Time spent</div>
                    </div>
                </div>

                <!-- Benchmark Comparison -->
                <div class="pt-6 border-t border-[#E3DFD5] text-center">
                    <div class="flex items-center justify-center mb-2">
                        <svg class="w-4 h-4 text-accent mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-sm font-semibold text-ink">Preparation Benchmark</span>
                    </div>
                    <p class="text-xs text-stone mb-2">
                        Your score of <strong class="text-ink font-semibold">{{ number_format($score, 1) }}%</strong> is 
                        @if($isAboveBenchmark)
                            <span class="text-correct font-semibold">above</span>
                        @else
                            <span class="text-wrong font-semibold">below</span>
                        @endif
                        the PhilNITS preparation benchmark of <strong class="text-ink font-semibold">60%</strong>.
                    </p>
                    <p class="text-xs text-stone/80">
                        Note: This benchmark is for internal preparation tracking and should not be considered a guarantee of official examination results.
                    </p>
                </div>
            </div>

            <!-- Topic Performance -->
            @if(count($topicPerformance) > 0)
                <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden p-6 md:p-8 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-serif font-semibold text-ink">Topic performance breakdown</h3>
                        <span class="text-xs font-mono text-stone">Passing standard: 60%</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($topicPerformance as $perf)
                            <div class="border border-[#E3DFD5] bg-paper/40 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-2.5 h-2.5 rounded-full mr-2.5 bg-accent"></div>
                                        <span class="text-sm font-medium text-ink">{{ $perf['topic_name'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-mono font-bold text-ink">{{ $perf['percentage'] }}%</span>
                                        <span class="text-xs font-mono text-stone ml-2">({{ $perf['correct'] }}/{{ $perf['total'] }})</span>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="w-full bg-[#E3DFD5] rounded-full h-2 overflow-hidden">
                                    <div 
                                        class="h-2 rounded-full transition-all duration-300 {{ $perf['percentage'] >= 60 ? 'bg-correct' : 'bg-wrong' }}" 
                                        style="width: {{ $perf['percentage'] }}%"
                                    ></div>
                                </div>
                                
                                @if($perf['percentage'] < 60)
                                    <div class="mt-2 text-xs font-mono text-wrong font-medium">
                                        Needs improvement — below 60% benchmark
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Weak Areas & Recommendations -->
            @if($weakAreas->count() > 0)
                <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden p-6 md:p-8 shadow-xs">
                    <h3 class="text-lg font-serif font-semibold text-ink mb-1">Focus areas</h3>
                    <p class="text-xs text-stone mb-6">
                        Based on your performance, these topics require focused review. We recommend practicing these questions first.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($weakAreas as $area)
                            <div class="border border-[#E3DFD5] bg-paper/60 p-5 rounded-xl flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-sm text-ink">{{ $area['topic_name'] }}</h4>
                                    <p class="text-xs font-mono text-stone mt-1">
                                        {{ $area['correct'] }} of {{ $area['total'] }} correct ({{ $area['percentage'] }}%)
                                    </p>
                                </div>
                                <a 
                                    href="/topics/{{ $area['topic_id'] }}"
                                    class="inline-flex items-center space-x-1.5 px-4 py-2 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-xs hover:shadow-md transition-all shrink-0 ml-4"
                                >
                                    <span>Review topic</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recommended Actions -->
            @if($recommendedTopics->count() > 0)
                <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden p-6 md:p-8 shadow-xs">
                    <h3 class="text-lg font-serif font-semibold text-ink mb-4">Recommended next steps</h3>
                    
                    <div class="space-y-3">
                        @foreach($recommendedTopics as $idx => $topic)
                            <div class="flex items-center justify-between p-4 border border-[#E3DFD5] bg-paper/40 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-paper border border-[#D9D5C9] text-accent rounded-xl flex items-center justify-center font-mono font-bold text-xs mr-3 shadow-xs">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-ink">{{ $topic['topic_name'] }}</p>
                                        <p class="text-xs text-stone">Practice questions in this topic</p>
                                    </div>
                                </div>
                                <a 
                                    href="/topics/{{ $topic['topic_id'] }}"
                                    class="inline-flex items-center space-x-1.5 px-4 py-2 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-xs hover:shadow-md transition-all"
                                >
                                    <span>Practice</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center items-center gap-4 pt-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-1.5 px-5 py-2.5 border border-[#D9D5C9] bg-surface text-accent rounded-xl text-xs font-medium hover:bg-paper transition-all shadow-xs">
                    <span>← Back to dashboard</span>
                </a>
                
                <a href="{{ route('assessment.index') }}" 
                   class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <span>Take another assessment →</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

