<div class="space-y-6 sm:space-y-8">
    
    <!-- Paxi Hero Card (matches design system) -->
    <div class="bg-surface border border-[#E3DFD5] rounded-3xl p-6 sm:p-8 shadow-xs relative overflow-hidden transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-xl space-y-4">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
                    <span>Your Review Companion · Paxi</span>
                </div>
                
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ink tracking-tight">
                    Welcome back, {{ Auth::user()->name }}!
                </h1>
                
                <p class="text-sm sm:text-base text-stone leading-relaxed">
                    Paxi is ready to study with you. Consistent daily practice is the fastest path to passing the IT Passport exam!
                </p>

                <div class="flex flex-wrap items-center gap-2.5 pt-2">
                    <a href="{{ route('topics.index') }}" 
                       class="inline-flex items-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs sm:text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>Continue studying</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>

                    <a href="{{ route('planner.index') }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-paper border border-[#D9D5C9] text-accent hover:bg-surface text-xs sm:text-sm font-semibold rounded-xl shadow-xs hover:-translate-y-0.5 transition-all">
                        <span>Study Planner</span>
                    </a>

                    @if($activeMistakes->count() > 0)
                        <a href="{{ route('mistakes.index') }}" 
                           class="inline-flex items-center space-x-1.5 px-4 py-2.5 bg-wrong-surface border border-wrong/30 text-wrong hover:bg-wrong hover:text-surface text-xs sm:text-sm font-semibold rounded-xl shadow-xs hover:-translate-y-0.5 transition-all">
                            <span>Mistake Bank</span>
                            <span class="px-1.5 py-0.2 bg-wrong text-surface text-[10px] rounded-full font-bold">{{ $activeMistakes->count() }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Paxi Hero Mascot -->
            <div class="flex shrink-0 items-center justify-center lg:justify-end py-2">
                <x-mascot variant="wave" :size="120" class="w-[90px] h-[90px] sm:w-[120px] sm:h-[120px]" />
            </div>
        </div>

        <!-- Hero Stats Row (matching mockup tiles) -->
        <div class="mt-8 pt-6 border-t border-[#E3DFD5] grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Completed Topics -->
            <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-stone block">Completed Topics</span>
                    <span class="text-lg font-bold text-ink">{{ $completedTopics }} / {{ max(1, $totalTopics) }}</span>
                </div>
            </div>

            <!-- Practice Questions -->
            <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-stone block">Practice Questions</span>
                    <span class="text-lg font-bold text-ink">{{ number_format($practiceQuestionsCount) }}</span>
                </div>
            </div>

            <!-- Avg. Score -->
            <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-4 flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-stone block">Avg. Score</span>
                    <span class="text-lg font-bold text-ink">{{ $averageScore !== null ? $averageScore . '%' : ($currentScore !== null ? number_format($currentScore, 0) . '%' : 'N/A') }}</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar (matching mockup) -->
        <div class="mt-6 pt-5 border-t border-[#E3DFD5]">
            <div class="flex items-center justify-between text-xs font-semibold text-ink mb-2">
                <span>Your Progress</span>
                <span class="font-mono text-stone">{{ $progressPercentage }}%</span>
            </div>
            <div class="w-full bg-[#E3DFD5] rounded-full h-2.5 overflow-hidden">
                <div class="bg-accent h-2.5 rounded-full transition-all duration-500 ease-out" 
                     style="width: {{ max(4, $progressPercentage) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Assessment Readiness Overview (Readiness for the Real Exam) -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-ink tracking-tight">Assessment Overview</h2>
                <p class="text-xs sm:text-sm text-stone">Your score and benchmark readiness for the actual IT Passport exam.</p>
            </div>
            @if($hasCompletedInitialAssessment)
                <a href="{{ route('assessment.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-accent hover:underline self-start sm:self-auto">
                    <span>Retake assessment</span>
                    <span>&rarr;</span>
                </a>
            @endif
        </div>

        @if(!$hasCompletedInitialAssessment)
            <!-- Unassessed State: Invitation Banner with Benchmark Gauge -->
            <div class="bg-surface border border-[#D9D5C9] rounded-3xl p-6 sm:p-8 shadow-xs">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8">
                    <div class="space-y-3 max-w-xl text-center sm:text-left">
                        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs font-medium shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                            <span>OFFICIAL BENCHMARK: {{ $benchmark }}% PASSING SCORE</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-ink tracking-tight">
                            Measure your exam readiness
                        </h3>
                        <p class="text-xs sm:text-sm text-stone leading-relaxed">
                            Take the assessment to measure your readiness against the official 60% IT Passport passing threshold. Paxi will diagnose your strengths and pinpoint high-yield focus areas.
                        </p>
                        <div class="pt-2 flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <a href="{{ route('assessment.index') }}" 
                               class="inline-flex items-center space-x-2 px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-xs sm:text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                <span>Take Assessment</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                            <span class="text-xs font-mono text-stone">Takes ~10–15 mins</span>
                        </div>
                    </div>

                    <!-- Readiness Benchmark Placeholder Gauge -->
                    <div class="bg-paper/70 border border-[#E3DFD5] rounded-2xl p-5 sm:p-6 shrink-0 w-full sm:w-80 text-center space-y-3">
                        <div class="flex items-center justify-between text-xs font-mono text-stone">
                            <span>READINESS GAUGE</span>
                            <span class="font-bold text-accent">TARGET: {{ $benchmark }}%</span>
                        </div>
                        <div class="py-2">
                            <div class="text-4xl font-extrabold text-stone/50 font-mono">--.-<span class="text-xl">%</span></div>
                            <p class="text-[11px] font-mono text-stone mt-1">Pending initial assessment</p>
                        </div>
                        <div class="w-full bg-[#E3DFD5] rounded-full h-2 overflow-hidden">
                            <div class="bg-accent/30 h-2 rounded-full w-0"></div>
                        </div>
                        <div class="pt-1 flex items-center justify-between text-[10px] font-mono text-stone/80">
                            <span>0%</span>
                            <span class="font-bold text-accent">Pass: {{ $benchmark }}%</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <!-- Score Summary Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Current Score -->
            <div class="bg-surface border border-[#E3DFD5] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
                <span class="text-xs font-mono uppercase tracking-wider text-stone font-semibold block mb-2">Your current score</span>
                <div class="flex items-baseline">
                    <span class="text-4xl font-extrabold text-accent tracking-tight">{{ number_format($currentScore ?? 0, 1) }}</span>
                    <span class="text-xl text-stone ml-1 font-mono">%</span>
                </div>
                
                <div class="mt-4">
                    @if($currentScore >= $benchmark)
                        <div class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-correct-surface text-correct border border-correct/20">
                            <span>✓</span>
                            <span>Above preparation benchmark</span>
                        </div>
                    @else
                        <div class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-wrong-surface text-wrong border border-wrong/20">
                            <span>⚠</span>
                            <span>Below preparation benchmark</span>
                        </div>
                    @endif
                </div>
                
                <p class="mt-3 text-xs text-stone">
                    Target: {{ $benchmark }}% preparation benchmark
                </p>
            </div>

            <!-- Recent Performance -->
            <div class="bg-surface border border-[#E3DFD5] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
                <span class="text-xs font-mono uppercase tracking-wider text-stone font-semibold block mb-2">Recent assessments</span>
                <div class="text-4xl font-extrabold text-ink tracking-tight mb-1">
                    {{ $recentAssessments->count() }}
                </div>
                <p class="text-xs text-stone mb-4">Completed this month</p>
                
                @if($recentAssessments->count() > 0)
                    <div class="text-xs font-mono text-stone bg-paper px-3 py-1.5 rounded-lg border border-[#E3DFD5] inline-block">
                        Latest: <span class="font-bold text-ink">{{ number_format($recentAssessments->first()->score_percentage, 1) }}%</span>
                        <span class="text-stone">({{ $recentAssessments->first()->submitted_at->format('M j') }})</span>
                    </div>
                @endif
            </div>

            <!-- Active Mistakes -->
            <div class="bg-surface border border-[#E3DFD5] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
                <span class="text-xs font-mono uppercase tracking-wider text-stone font-semibold block mb-2">Active mistakes</span>
                <div class="text-4xl font-extrabold text-wrong tracking-tight mb-1">
                    {{ $activeMistakes->count() }}
                </div>
                <p class="text-xs text-stone mb-4">Questions to review</p>
                
                @if($activeMistakes->count() > 0)
                    <a href="{{ route('mistakes.index') }}" class="inline-flex items-center space-x-1 text-xs font-semibold text-wrong bg-wrong-surface px-3 py-1.5 rounded-lg border border-wrong/30 hover:bg-wrong hover:text-surface transition-colors">
                        <span>Review mistakes queue</span>
                        <span>&rarr;</span>
                    </a>
                @else
                    <span class="text-xs font-mono text-correct font-medium">No unresolved mistakes!</span>
                @endif
            </div>
        </div>
    </div>

        <!-- Paxi's Companion Hub (Tips & Recommendations) -->
        <div class="bg-paper border border-[#E3DFD5] rounded-3xl p-6 sm:p-7 shadow-xs">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-start space-x-4">
                    <div class="space-y-1 max-w-xl">
                        <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-accent-tint text-accent text-[11px] font-mono font-semibold">
                            <span>PAXI'S COMPANION ADVICE</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-ink">
                            @if($activeMistakes->count() > 0)
                                Prioritize: Clear your {{ $activeMistakes->count() }} question mistake bank!
                            @elseif(count($weakTopics) > 0)
                                Recommendation: Drill your focus area "{{ $weakTopics->first()->name }}"
                            @else
                                Keep up the great work: Ready for a mock assessment?
                            @endif
                        </h3>
                        <p class="text-xs sm:text-sm text-stone leading-relaxed">
                            @if($activeMistakes->count() > 0)
                                Paxi tracks every incorrect answer so you can master them without repeating errors on exam day.
                            @elseif(count($weakTopics) > 0)
                                Spending 15 minutes reviewing this topic will give you the biggest boost in your IT Passport readiness score.
                            @else
                                Consistent practice cements concepts in your memory. Try reviewing daily study goals in your planner!
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 shrink-0 w-full md:w-auto">
                    @if($activeMistakes->count() > 0)
                        <a href="{{ route('mistakes.index') }}" class="w-full md:w-auto text-center inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-wrong text-surface hover:bg-wrong/90 text-xs font-semibold rounded-xl shadow-xs transition-all">
                            <span>Open Mistake Bank</span>
                            <span>&rarr;</span>
                        </a>
                    @elseif(count($weakTopics) > 0)
                        <a href="{{ route('topics.show', $weakTopics->first()) }}" class="w-full md:w-auto text-center inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-xs transition-all">
                            <span>Practice Focus Topic</span>
                            <span>&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('planner.index') }}" class="w-full md:w-auto text-center inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-xs transition-all">
                            <span>View Study Planner</span>
                            <span>&rarr;</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Focus & Last Assessment Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Weak Topics Card -->
            <div class="bg-surface border border-[#E3DFD5] rounded-2xl overflow-hidden shadow-xs">
                <div class="p-5 sm:p-6 border-b border-[#E3DFD5] bg-panel">
                    <h3 class="text-base font-bold text-ink">Focus areas</h3>
                    <p class="text-xs text-stone mt-0.5">Topics showing weaker understanding</p>
                </div>
                
                @if(count($weakTopics) > 0)
                    <div class="p-5 sm:p-6">
                        <div class="space-y-3">
                            @foreach($weakTopics as $topic)
                                <div class="flex items-center justify-between p-3 rounded-xl border border-[#E3DFD5] bg-paper/50 hover:bg-paper transition-colors">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-accent"></div>
                                        <span class="text-sm font-semibold text-ink">{{ $topic->name }}</span>
                                    </div>
                                    <a href="{{ route('topics.show', $topic) }}" class="text-xs font-semibold text-accent hover:underline">Practice &rarr;</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <p class="text-sm text-stone">No weak areas identified yet. Keep practicing!</p>
                    </div>
                @endif
            </div>

            <!-- Last Assessment -->
            <div class="bg-surface border border-[#E3DFD5] rounded-2xl overflow-hidden shadow-xs">
                <div class="p-5 sm:p-6 border-b border-[#E3DFD5] bg-panel">
                    <h3 class="text-base font-bold text-ink">Last assessment</h3>
                    <p class="text-xs text-stone mt-0.5">Review your most recent performance</p>
                </div>
                
                @if($recentAssessments->count() > 0 && $recentAssessments->first())
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div class="text-3xl font-extrabold text-accent">
                                {{ number_format($recentAssessments->first()->score_percentage, 1) }}%
                            </div>
                            <div class="text-right text-xs font-mono text-stone">
                                <div>{{ $recentAssessments->first()->submitted_at->diffForHumans() }}</div>
                                <div class="mt-0.5">{{ $recentAssessments->first()->total_questions }} questions</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('assessment.results', $recentAssessments->first()) }}" 
                           class="inline-flex items-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-xs transition-all">
                            <span>View full results</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <a href="{{ route('assessment.index') }}" class="text-sm text-accent hover:underline font-semibold">
                            Take your first assessment &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
