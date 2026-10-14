<div class="space-y-6 sm:space-y-8">
    
    @if(session('pretest_required') || session('warning'))
        <div class="bg-surface border-2 border-accent/40 rounded-2xl p-4 sm:p-5 shadow-sm flex items-start space-x-3.5 animate-fadeIn">
            <div class="w-9 h-9 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-ink">Pre-Test Assessment Required</h4>
                <p class="text-xs sm:text-sm text-stone mt-0.5">
                    {{ session('warning') ?? 'To provide tailored review materials, you need to complete the initial pre-test exam first.' }}
                </p>
                <div class="mt-3">
                    <a href="{{ route('assessment.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-1.5 bg-accent text-surface text-xs font-semibold rounded-lg hover:bg-accent/90 transition-colors">
                        <span>Begin Pre-Test Now</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Unified Top Assessment & Readiness Hero Card -->
    <div class="bg-surface border border-[#D9D5C9] rounded-3xl p-6 sm:p-8 lg:p-10 shadow-xs relative overflow-hidden transition-all">
        <!-- Top Pill & Large Mascot Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs font-semibold shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
                    <span>Your Review Companion · Paxi</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ink tracking-tight">
                    Welcome back, {{ Auth::user()->name }}!
                </h1>
            </div>

            <!-- Paxi Mascot (Enlarged) -->
            <div class="flex shrink-0 items-center justify-center sm:justify-end">
                <x-mascot variant="wave" :size="130" class="w-[100px] h-[100px] sm:w-[130px] sm:h-[130px] drop-shadow-sm hover:scale-105 transition-transform duration-300" />
            </div>
        </div>

        @if(!$hasCompletedInitialAssessment)
            <!-- Unassessed State: 12-column layout (7 cols left, 5 cols right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center pt-2 border-t border-[#E3DFD5]/80">
                <!-- Left: Explanation & Action -->
                <div class="lg:col-span-7 space-y-4">
                    <p class="text-sm sm:text-base text-stone leading-relaxed">
                        Welcome to your IT Passport preparation! Take the initial pre-test assessment first so Paxi can diagnose your baseline across Strategy, Management, and Technology.
                    </p>

                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center gap-3">
                        <a href="{{ route('assessment.index') }}" 
                           class="inline-flex items-center justify-center space-x-2 px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all ring-2 ring-accent/30 ring-offset-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Take Pre-Test Assessment</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>

                        <span class="inline-flex items-center space-x-1.5 px-4 py-3 bg-paper border border-[#D9D5C9] text-stone/60 text-xs sm:text-sm font-medium rounded-xl cursor-not-allowed self-start sm:self-auto" title="Complete pre-test to unlock study topics">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Study Topics (Locked)</span>
                        </span>
                    </div>

                    <div class="pt-1">
                        <span class="text-xs font-mono text-stone inline-flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-stone" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Simulates Official Exam (100 Qs · 120 Mins)</span>
                        </span>
                    </div>
                </div>

                <!-- Right: Readiness Gauge Card showing Official ITPEC Syllabus -->
                <div class="lg:col-span-5">
                    <div class="bg-paper border border-[#E3DFD5] rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between text-xs font-mono border-b border-[#E3DFD5] pb-3">
                            <div>
                                <span class="font-bold text-ink uppercase tracking-wider block">Official ITPEC Syllabus</span>
                                <span class="text-[10px] text-stone">April 2024 Exam Alignment</span>
                            </div>
                            <span class="text-accent font-semibold bg-surface px-2.5 py-1 rounded-full border border-[#D9D5C9] text-[11px]">Pass: ≥60%</span>
                        </div>
                        
                        <!-- Overall Score Status -->
                        <div class="text-center py-2.5 bg-surface rounded-xl border border-[#E3DFD5]/60">
                            <div class="text-3xl sm:text-4xl font-extrabold text-stone/40 font-mono tracking-tight">--.-<span class="text-lg font-normal">%</span></div>
                            <p class="text-[11px] font-mono text-stone mt-0.5">Pending pre-test assessment</p>
                        </div>

                        <!-- 3 ITPEC Syllabus Domains Breakdown with Real Topics -->
                        <div class="space-y-3.5 pt-1">
                            <div class="text-[11px] font-mono text-stone font-semibold uppercase tracking-wider flex items-center justify-between">
                                <span>3 Exam Fields & Topics</span>
                                <span>Min. 30% Pass</span>
                            </div>

                            @foreach($syllabusFields as $fieldKey => $field)
                                <div class="bg-surface/70 border border-[#E3DFD5] rounded-xl p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded {{ $field['badge_color'] }}">
                                                {{ $field['field_code'] }}
                                            </span>
                                            <span class="text-xs font-bold text-ink">{{ $field['name'] }}</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-stone">{{ $field['official_weight'] }}</span>
                                    </div>

                                    <!-- Topics under this field -->
                                    <div class="flex flex-wrap gap-1.5 pt-1">
                                        @foreach($field['topics'] as $topic)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] bg-paper text-stone border border-[#E3DFD5]/70" title="{{ $topic->description }}">
                                                {{ $topic->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Score indicator bar -->
                                    <div class="w-full bg-[#E3DFD5] rounded-full h-1.5 overflow-hidden mt-1">
                                        <div class="{{ $field['bar_color'] }} h-1.5 rounded-full w-0 transition-all duration-500"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Rule -->
                        <div class="pt-2 border-t border-[#E3DFD5] flex items-center justify-between text-[10px] font-mono text-stone">
                            <span>100 Qs · 120 Mins</span>
                            <span class="font-semibold text-accent">Pass: Overall ≥60% & Each Field ≥30%</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Assessed State: Greeting, Action & Score Overview -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="max-w-xl space-y-4">
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

                        <a href="{{ route('assessment.index') }}" class="inline-flex items-center space-x-1 text-xs font-semibold text-accent hover:underline px-2 py-1">
                            <span>Retake Assessment &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

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
</div>
