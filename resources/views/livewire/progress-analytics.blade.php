<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    
    <!-- Page Header & Period Selector -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-line/60">
        <div>
            <h1 class="text-2xl font-semibold text-ink">Analytics & progress</h1>
            <p class="text-sm text-stone mt-0.5">Track your exam readiness, score trends, and topic mastery.</p>
        </div>
        
        <!-- Period Selector Pills -->
        <div class="inline-flex p-1 bg-paper border border-line rounded-lg space-x-1 shrink-0">
            @foreach(['daily', 'weekly', 'monthly', '30days', 'all'] as $period)
                <button 
                    wire:click="setPeriod('{{ $period }}')"
                    class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ $currentPeriod === $period ? 'bg-accent text-surface shadow-sm' : 'text-stone hover:text-ink hover:bg-surface' }}">
                    @switch($period)
                        @case('daily') Today @break
                        @case('weekly') This week @break
                        @case('monthly') This month @break
                        @case('30days') Last 30 days @break
                        @case('all') All time @break
                    @endswitch
                </button>
            @endforeach
        </div>
    </div>

    <!-- Key Metrics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Overall Score -->
        <div class="bg-surface rounded-xl border border-line p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-stone">Overall score</span>
                    <span class="p-1.5 rounded-lg bg-accent-tint text-accent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </span>
                </div>
                <p class="text-3xl font-semibold text-accent">{{ number_format($overallScore, 1) }}%</p>
                <p class="text-xs text-stone mt-1">From {{ $totalAssessments }} {{ Str::plural('assessment', $totalAssessments) }}</p>
            </div>
            
            @if($bestScore > 0 && $worstScore > 0)
                <div class="mt-4 pt-3 border-t border-line text-xs text-stone flex justify-between">
                    <span>Best: <strong class="text-ink font-medium">{{ $bestScore }}%</strong></span>
                    <span>Worst: <strong class="text-ink font-medium">{{ $worstScore }}%</strong></span>
                </div>
            @endif
        </div>

        <!-- Mistake Resolution -->
        <div class="bg-surface rounded-xl border border-line p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-stone">Mistake resolution</span>
                    <span class="p-1.5 rounded-lg bg-accent-tint text-accent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-3xl font-semibold text-ink">
                        {{ $mistakeStats['resolved_mistakes'] }}/{{ $mistakeStats['total_mistakes'] }}
                    </p>
                    @if($mistakeStats['total_mistakes'] > 0)
                        <span class="text-base font-semibold text-accent">
                            {{ number_format(($mistakeStats['resolved_mistakes'] / max(1, $mistakeStats['total_mistakes'])) * 100, 0) }}%
                        </span>
                    @else
                        <span class="text-base font-semibold text-correct">100%</span>
                    @endif
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-t border-line text-xs text-stone">
                @if($mistakeStats['unresolved_mistakes'] > 0)
                    <span class="text-wrong font-medium">{{ $mistakeStats['unresolved_mistakes'] }} unresolved</span> questions
                @else
                    <span class="text-correct font-medium">All mastered!</span>
                @endif
            </div>
        </div>

        <!-- Practice Activity -->
        <div class="bg-surface rounded-xl border border-line p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-stone">Practice activity</span>
                    <span class="p-1.5 rounded-lg bg-accent-tint text-accent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <p class="text-3xl font-semibold text-ink">{{ number_format($questionsAnswered) }}</p>
                <p class="text-xs text-stone mt-1">Questions answered (last 7 days)</p>
            </div>
            
            <div class="mt-4 pt-3 border-t border-line text-xs text-stone">
                Active practice status
            </div>
        </div>

        <!-- Preparation Level -->
        <div class="bg-surface rounded-xl border border-line p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-stone">Preparation level</span>
                    <span class="p-1.5 rounded-lg bg-accent-tint text-accent">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                
                @if($overallScore >= 60)
                    <div class="flex items-center">
                        <span class="text-2xl font-semibold text-correct">Ready</span>
                    </div>
                    <p class="text-xs text-stone mt-1">Above 60% benchmark</p>
                @elseif($overallScore > 0)
                    <div class="flex items-center">
                        <span class="text-2xl font-semibold text-ink">In progress</span>
                    </div>
                    <p class="text-xs text-stone mt-1">{{ number_format(60 - $overallScore, 1) }}% to reach benchmark</p>
                @else
                    <div class="flex items-center">
                        <span class="text-2xl font-semibold text-stone">Not started</span>
                    </div>
                    <p class="text-xs text-stone mt-1">Take initial assessment</p>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-line text-xs text-stone">
                Benchmark: 60% pass mark
            </div>
        </div>
    </div>

    <!-- Data Sections vs Empty Grid -->
    @if(count($assessmentHistory) > 1)
        <!-- Full Trend Chart Section -->
        <div class="bg-surface rounded-xl border border-line overflow-hidden p-6">
            <h3 class="text-lg font-semibold text-ink mb-4">Assessment performance trend</h3>
            
            <div class="relative h-64 bg-paper/30 rounded-lg p-3 border border-line">
                <canvas id="trendChart"></canvas>
            </div>
            
            <!-- History Table -->
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-line">
                    <thead class="bg-paper">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Duration</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Score</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-surface divide-y divide-line">
                        @foreach(array_reverse($assessmentHistory) as $assessment)
                            <tr class="hover:bg-paper/40 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-ink">
                                    {{ date('M j, Y', strtotime($assessment['started_at'])) }}<br>
                                    <span class="text-xs text-stone">{{ date('g:i a', strtotime($assessment['started_at'])) }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-stone">
                                    {{ number_format($assessment['time_spent_seconds'] ?? 0, 0) }}s
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded {{ $assessment['score'] >= 60 ? 'bg-correct-surface text-correct' : 'bg-wrong-surface text-wrong' }}">
                                        {{ number_format($assessment['score'], 1) }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-stone">
                                    {{ ucfirst(str_replace('_', ' ', $assessment['status'])) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Topic Performance Breakdown -->
        <div class="bg-surface rounded-xl border border-line overflow-hidden">
            <div class="px-6 py-4 border-b border-line bg-paper/30">
                <h3 class="text-lg font-semibold text-ink">Topic performance</h3>
                <p class="text-xs text-stone mt-0.5">Your strongest and weakest topics based on assessment performance</p>
            </div>

            @if(count($topicPerformance) > 0)
                <div class="divide-y divide-line">
                    @foreach($topicPerformance as $idx => $topic)
                        <div class="p-5 hover:bg-paper/30 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-ink">{{ $topic['topic_name'] }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-paper border border-line text-stone">
                                            {{ $topic['attempts'] }} {{ Str::plural('attempt', $topic['attempts']) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <span class="text-lg font-semibold text-accent">{{ number_format($topic['average_score'], 1) }}%</span>
                                    <p class="text-xs text-stone">{{ $topic['correct_answers'] }}/{{ $topic['total_questions'] }} correct</p>
                                </div>
                            </div>

                            <div class="w-full bg-line rounded-full h-2">
                                <div 
                                    class="h-2 rounded-full bg-accent transition-all duration-500"
                                    style="width: {{ $topic['average_score'] }}%"
                                ></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @else
        <!-- Empty State Side-by-Side 2-Column Grid (Replaces giant sparse empty rectangles) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Empty Trend Card -->
            <div class="bg-surface rounded-xl border border-line p-8 text-center flex flex-col justify-center items-center min-h-[260px]">
                <div class="w-12 h-12 rounded-full bg-paper border border-line flex items-center justify-center text-stone mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-ink mb-1">Assessment performance trend</h3>
                <p class="text-xs text-stone max-w-sm mb-5 leading-relaxed">
                    Complete your first assessment to track your score progression over time.
                </p>
                <a href="{{ route('assessment.index') }}" class="px-5 py-2.5 bg-ink text-surface text-xs font-medium rounded-lg hover:bg-stone transition-colors inline-flex items-center">
                    Take assessment →
                </a>
            </div>

            <!-- Empty Topic Performance Card -->
            <div class="bg-surface rounded-xl border border-line p-8 text-center flex flex-col justify-center items-center min-h-[260px]">
                <div class="w-12 h-12 rounded-full bg-paper border border-line flex items-center justify-center text-stone mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-ink mb-1">Topic performance breakdown</h3>
                <p class="text-xs text-stone max-w-sm mb-5 leading-relaxed">
                    Topic insights and category strengths will appear here after taking assessments.
                </p>
                <a href="{{ route('topics.index') }}" class="px-5 py-2.5 border border-line text-stone hover:text-ink hover:bg-paper text-xs font-medium rounded-lg transition-colors inline-flex items-center">
                    Browse topics
                </a>
            </div>

        </div>
    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:navigated', () => {
    // Initialize Chart.js with palette tokens: Navy for data line (#1F3A5F) & Line color for grid (#E3DFD5)
    const canvas = document.getElementById('trendChart');
    if (canvas && @json($assessmentHistory)) {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: @json($trendGraphData['labels']),
                datasets: [{
                    label: 'Assessment score (%)',
                    data: @json($trendGraphData['scores']),
                    borderColor: '#1F3A5F',
                    backgroundColor: 'rgba(31, 58, 95, 0.08)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#1F3A5F',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#E3DFD5'
                        },
                        ticks: {
                            color: '#6E6A60',
                            font: { family: 'Inter' }
                        }
                    },
                    x: {
                        grid: {
                            color: '#E3DFD5'
                        },
                        ticks: {
                            color: '#6E6A60',
                            font: { family: 'Inter' }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

