<div class="space-y-6 sm:space-y-8">
    
    <!-- Welcome Message Banner -->
    <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 sm:p-7 shadow-xs">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs mb-3 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-correct animate-pulse"></span>
            <span>Study Loop · IT Passport Candidate</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-ink tracking-tight mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-stone">Continue your examination preparation journey.</p>
    </div>

    @if(!$hasCompletedInitialAssessment)
        <!-- Primary CTA: Take Initial Assessment -->
        <div class="bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden p-6 sm:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="space-y-2 max-w-xl">
                    <span class="text-xs font-mono font-semibold text-accent uppercase tracking-wider block">Diagnostic Benchmark</span>
                    <h3 class="text-xl sm:text-2xl font-bold text-ink">Take your initial assessment</h3>
                    <p class="text-sm text-stone leading-relaxed">
                        Start with our comprehensive initial assessment to determine your current knowledge level and identify areas for improvement.
                    </p>
                    
                    <div class="pt-2 flex items-center space-x-4 text-xs font-mono text-stone">
                        <span class="inline-flex items-center bg-paper px-2.5 py-1 rounded-md border border-[#E3DFD5]">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $recentAssessments->count() }} previous assessments
                        </span>
                    </div>
                </div>
                
                <a href="{{ route('assessment.index') }}" 
                   class="inline-flex items-center justify-center space-x-2 px-6 py-3 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 shrink-0">
                    <span>Start now</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </div>

    @else
        <!-- Score Summary Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Current Score -->
            <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
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
            <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
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
            <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 shadow-xs hover:shadow-md transition-shadow">
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
            <div class="bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden shadow-xs">
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
            <div class="bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden shadow-xs">
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

