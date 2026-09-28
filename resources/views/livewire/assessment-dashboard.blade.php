<div class="space-y-6">
    
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-primary-600 to-blue-700 rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="opacity-90">Continue your preparation journey.</p>
    </div>

    @if(!$hasCompletedInitialAssessment)
        <!-- Primary CTA: Take Initial Assessment -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Take Your Initial Assessment</h3>
                        <p class="text-gray-600 mb-4">
                            Start with our comprehensive initial assessment to determine your current knowledge level and identify areas for improvement.
                        </p>
                        
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2  7 7 0 017 7v6a2 2 0 11-4 0v-3a1 1 0 10-2 0v3a1 1 0 10-2 0v-3a1 1 0 10-2 0V7a1 1 0 112 0v3a1 1 0 102 0V5a3 3 0 013-3h1a3 3 0 013 3v2a3 3 0 01-3 3H4a3 3 0 01-3-3V5z" clip-rule="evenodd"/>
                                </svg>
                                {{ $recentAssessments->count() }} previous assessments
                            </span>
                        </div>
                    </div>
                    
                    <a href="{{ route('assessment.index') }}" 
                       class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm whitespace-nowrap">
                        Start Now →
                    </a>
                </div>
            </div>
        </div>

    @else
        <!-- Score Summary Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Current Score -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Your Current Score</h3>
                <div class="flex items-baseline">
                    <span class="text-4xl font-bold text-primary-700">{{ number_format($currentScore ?? 0, 1) }}</span>
                    <span class="text-xl text-gray-600 ml-1">%</span>
                </div>
                
                @if($currentScore >= $benchmark)
                    <div class="mt-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Above Preparation Benchmark
                    </div>
                @else
                    <div class="mt-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Below Preparation Benchmark
                    </div>
                @endif
                
                <p class="mt-3 text-xs text-gray-500">
                    Target: {{ $benchmark }}% preparation benchmark
                </p>
            </div>

            <!-- Recent Performance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Recent Assessments</h3>
                <div class="text-2xl font-bold text-gray-900 mb-1">
                    {{ $recentAssessments->count() }}
                </div>
                <p class="text-sm text-gray-600 mb-2">Completed this month</p>
                
                @if($recentAssessments->count() > 0)
                    <div class="flex items-center text-xs text-gray-500">
                        Latest: {{ number_format($recentAssessments->first()->score_percentage, 1) }}%
                        <span class="ml-2">on {{ $recentAssessments->first()->submitted_at->format('M j') }}</span>
                    </div>
                @endif
            </div>

            <!-- Active Mistakes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Active Mistakes</h3>
                <div class="text-2xl font-bold text-red-600 mb-1">
                    {{ $activeMistakes->count() }}
                </div>
                <p class="text-sm text-gray-600 mb-2">Questions to review</p>
                
                @if($activeMistakes->count() > 0)
                    <a href="{{ route('mistakes.index') }}" class="inline-flex items-center text-xs text-primary-600 hover:text-primary-700 font-medium">
                        Review Mistakes →
                    </a>
                @endif
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Weak Topics Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Focus Areas</h3>
                    <p class="text-sm text-gray-600 mt-1">Topics showing weaker understanding</p>
                </div>
                
                @if(count($weakTopics) > 0)
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($weakTopics as $topic)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded mr-2" style="background-color: {{ $topic->color ?? '#3B82F6' }}"></div>
                                        <span class="text-sm font-medium text-gray-900">{{ $topic->name }}</span>
                                    </div>
                                    <a href="#" class="text-xs text-primary-600 hover:text-primary-700">Practice →</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-600">No weak areas identified yet. Keep practicing!</p>
                    </div>
                @endif
            </div>

            <!-- Last Assessment -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Last Assessment</h3>
                    <p class="text-sm text-gray-600 mt-1">Review your most recent performance</p>
                </div>
                
                @if($recentAssessments->count() > 0 && $recentAssessments->first())
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-3xl font-bold text-primary-700">
                                {{ number_format($recentAssessments->first()->score_percentage, 1) }}%
                            </div>
                            <div class="text-right text-sm text-gray-600">
                                <div>{{ $recentAssessments->first()->submitted_at->diffForHumans() }}</div>
                                <div class="text-xs mt-1">{{ $recentAssessments->first()->total_questions }} questions</div>
                            </div>
                        </div>
                        
                        <a href="{{ route('assessment.results', $recentAssessments->first()) }}" 
                           class="inline-block px-4 py-2 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700 transition-colors">
                            View Full Results →
                        </a>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <a href="{{ route('assessment.index') }}" class="text-primary-600 hover:text-primary-700">
                            Take your first assessment →
                        </a>
                    </div>
                @endif
            </div>
        </div>

    @endif
</div>
