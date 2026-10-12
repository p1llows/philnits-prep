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

            <!-- Passy Score Card (matching Assessment Results mockup) -->
            <div class="bg-surface rounded-3xl border border-[#E3DFD5] overflow-hidden p-6 sm:p-10 shadow-xs text-center">
                @php
                    $score = $assessment->score_percentage ?? 0;
                    $isAboveBenchmark = $score >= 60;
                    $timeFormatted = sprintf('%02d:%02d', floor(($assessment->time_spent_seconds ?? 0) / 60), ($assessment->time_spent_seconds ?? 0) % 60);
                @endphp

                <!-- Mascot (success or oops) -->
                <div class="mb-5 flex justify-center">
                    @if($isAboveBenchmark)
                        <x-mascot variant="passed" :size="140" class="hover:scale-105 transition-transform" label="Passy Success" />
                    @else
                        <x-mascot variant="oops" :size="140" class="hover:scale-105 transition-transform" label="Passy Try Again" />
                    @endif
                </div>

                <!-- Main Greeting & Feedback -->
                <h2 class="text-2xl sm:text-3xl font-extrabold text-ink tracking-tight mb-1.5">
                    {{ $isAboveBenchmark ? 'Great job, you passed!' : 'Keep practicing, you can do it!' }}
                </h2>
                
                <p class="text-sm sm:text-base text-stone mb-8">
                    You got <strong class="text-ink font-bold">{{ $assessment->correct_count }}</strong> out of <strong class="text-ink font-bold">{{ $assessment->total_questions }}</strong> questions correct.
                </p>

                <!-- 3 Stats Tiles (mockup style: Score, Time, Correct) -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto mb-8">
                    <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 sm:p-5 text-center">
                        <span class="text-xs font-mono uppercase tracking-wider text-stone block mb-1">Score</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-accent">
                            {{ number_format($score, 0) }}%
                        </div>
                    </div>

                    <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 sm:p-5 text-center">
                        <span class="text-xs font-mono uppercase tracking-wider text-stone block mb-1">Time</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-ink font-mono">
                            {{ $timeFormatted }}
                        </div>
                    </div>

                    <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 sm:p-5 text-center">
                        <span class="text-xs font-mono uppercase tracking-wider text-stone block mb-1">Correct</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-correct font-mono">
                            {{ $assessment->correct_count }} / {{ $assessment->total_questions }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (matching mockup) -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 max-w-md mx-auto">
                    <a href="#detailed-breakdown" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-xs sm:text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>View detailed answers</span>
                    </a>

                    <a href="{{ route('dashboard') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper text-xs sm:text-sm font-semibold rounded-xl transition-all shadow-xs hover:border-accent/40">
                        <span>Back to dashboard</span>
                    </a>
                </div>
            </div>

            <div id="detailed-breakdown"></div>

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

