<div class="space-y-6">
    
    <!-- Welcome Message Banner -->
    <div class="bg-surface border border-line rounded-xl p-5">
        <h2 class="text-xl font-medium text-ink mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="text-sm text-stone">Continue your preparation journey.</p>
    </div>

    @if(!$hasCompletedInitialAssessment)
        <!-- Primary CTA: Take Initial Assessment -->
        <div class="bg-surface border border-line rounded-xl overflow-hidden p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-medium text-ink mb-1">Take your initial assessment</h3>
                    <p class="text-sm text-stone mb-3">
                        Start with our comprehensive initial assessment to determine your current knowledge level and identify areas for improvement.
                    </p>
                    
                    <div class="flex items-center space-x-4 text-xs text-stone">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-stone" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 7 7 0 017 7v6a2 2 0 11-4 0v-3a1 1 0 10-2 0v3a1 1 0 10-2 0v-3a1 1 0 10-2 0V7a1 1 0 112 0v3a1 1 0 102 0V5a3 3 0 013-3h1a3 3 0 013 3v2a3 3 0 01-3 3H4a3 3 0 01-3-3V5z" clip-rule="evenodd"/>
                            </svg>
                            {{ $recentAssessments->count() }} previous assessments
                        </span>
                    </div>
                </div>
                
                <a href="{{ route('assessment.index') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 bg-ink text-paper text-sm font-medium rounded-lg hover:bg-black transition-colors shrink-0">
                    Start now →
                </a>
            </div>
        </div>

    @else
        <!-- Score Summary Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Current Score -->
            <div class="bg-surface border border-line rounded-xl p-5">
                <h3 class="text-xs font-medium text-stone mb-2">Your current score</h3>
                <div class="flex items-baseline">
                    <span class="text-3xl font-semibold text-accent">{{ number_format($currentScore ?? 0, 1) }}</span>
                    <span class="text-lg text-stone ml-1">%</span>
                </div>
                
                @if($currentScore >= $benchmark)
                    <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-correct-surface text-correct border border-line">
                        Above preparation benchmark
                    </div>
                @else
                    <div class="mt-3 inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-wrong-surface text-wrong border border-line">
                        Below preparation benchmark
                    </div>
                @endif
                
                <p class="mt-3 text-xs text-stone">
                    Target: {{ $benchmark }}% preparation benchmark
                </p>
            </div>

            <!-- Recent Performance -->
            <div class="bg-surface border border-line rounded-xl p-5">
                <h3 class="text-xs font-medium text-stone mb-2">Recent assessments</h3>
                <div class="text-3xl font-semibold text-ink mb-1">
                    {{ $recentAssessments->count() }}
                </div>
                <p class="text-xs text-stone mb-3">Completed this month</p>
                
                @if($recentAssessments->count() > 0)
                    <div class="flex items-center text-xs text-stone">
                        Latest: {{ number_format($recentAssessments->first()->score_percentage, 1) }}%
                        <span class="ml-2">on {{ $recentAssessments->first()->submitted_at->format('M j') }}</span>
                    </div>
                @endif
            </div>

            <!-- Active Mistakes -->
            <div class="bg-surface border border-line rounded-xl p-5">
                <h3 class="text-xs font-medium text-stone mb-2">Active mistakes</h3>
                <div class="text-3xl font-semibold text-ink mb-1">
                    {{ $activeMistakes->count() }}
                </div>
                <p class="text-xs text-stone mb-3">Questions to review</p>
                
                @if($activeMistakes->count() > 0)
                    <a href="{{ route('mistakes.index') }}" class="inline-flex items-center text-xs text-accent hover:underline font-medium">
                        Review mistakes →
                    </a>
                @endif
            </div>
        </div>

        <!-- Focus & Last Assessment Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Weak Topics Card -->
            <div class="bg-surface border border-line rounded-xl overflow-hidden">
                <div class="p-5 border-b border-line bg-paper/30">
                    <h3 class="text-base font-medium text-ink">Focus areas</h3>
                    <p class="text-xs text-stone mt-0.5">Topics showing weaker understanding</p>
                </div>
                
                @if(count($weakTopics) > 0)
                    <div class="p-5">
                        <div class="space-y-3">
                            @foreach($weakTopics as $topic)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2.5 h-2.5 rounded-full mr-2 bg-accent"></div>
                                        <span class="text-sm font-medium text-ink">{{ $topic->name }}</span>
                                    </div>
                                    <a href="{{ route('topics.show', $topic) }}" class="text-xs text-accent hover:underline">Practice →</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-5 text-center">
                        <p class="text-sm text-stone">No weak areas identified yet. Keep practicing!</p>
                    </div>
                @endif
            </div>

            <!-- Last Assessment -->
            <div class="bg-surface border border-line rounded-xl overflow-hidden">
                <div class="p-5 border-b border-line bg-paper/30">
                    <h3 class="text-base font-medium text-ink">Last assessment</h3>
                    <p class="text-xs text-stone mt-0.5">Review your most recent performance</p>
                </div>
                
                @if($recentAssessments->count() > 0 && $recentAssessments->first())
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-3xl font-semibold text-accent">
                                {{ number_format($recentAssessments->first()->score_percentage, 1) }}%
                            </div>
                            <div class="text-right text-xs text-stone">
                                <div>{{ $recentAssessments->first()->submitted_at->diffForHumans() }}</div>
                                <div class="mt-0.5">{{ $recentAssessments->first()->total_questions }} questions</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('assessment.results', $recentAssessments->first()) }}" 
                           class="inline-flex items-center px-4 py-2 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors">
                            View full results →
                        </a>
                    </div>
                @else
                    <div class="p-5 text-center">
                        <a href="{{ route('assessment.index') }}" class="text-sm text-accent hover:underline font-medium">
                            Take your first assessment →
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @endif
</div>

