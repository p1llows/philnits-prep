<div class="space-y-6 sm:space-y-8">
    
    <!-- Paxi Hero Card (matches design system) -->
    <div class="bg-surface border border-[#E3DFD5] rounded-3xl p-6 sm:p-8 shadow-xs relative overflow-hidden transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="max-w-xl space-y-4">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
                    <span>IT Passport Reviewer · Paxi</span>
                </div>
                
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-ink tracking-tight">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h1>
                
                <p class="text-sm sm:text-base text-stone leading-relaxed">
                    Ready to continue your IT Passport journey? Keep going, you're doing great!
                </p>

                <div class="pt-2">
                    <a href="{{ route('topics.index') }}" 
                       class="inline-flex items-center space-x-2 px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>Continue studying</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Paxi Hero Mascot -->
            <div class="flex shrink-0 items-center justify-center lg:justify-end py-2">
                <div class="relative">
                    <div class="absolute -inset-4 bg-accent-tint/40 rounded-full blur-xl pointer-events-none"></div>
                    <x-mascot variant="default" :size="140" label="Paxi study companion" class="relative z-10 drop-shadow-sm hover:scale-105 transition-transform duration-300" />
                </div>
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

    @if(!$hasCompletedInitialAssessment)
        <!-- Empty State (matching mockup design) -->
        <div class="bg-surface border border-[#E3DFD5] rounded-3xl p-8 sm:p-12 text-center shadow-xs">
            <div class="flex justify-center mb-5">
                <x-mascot variant="default" :size="130" label="Paxi Ready to Study" class="hover:scale-105 transition-transform" />
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-ink tracking-tight mb-2">
                No assessment history yet
            </h3>
            <p class="text-xs sm:text-sm text-stone max-w-md mx-auto leading-relaxed mb-6">
                Start your first practice test to track your progress and see your results here.
            </p>
            <div>
                <a href="{{ route('assessment.index') }}" 
                   class="inline-flex items-center space-x-2 px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <span>Start a practice test</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
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
