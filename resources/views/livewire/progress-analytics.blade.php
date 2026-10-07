<div class="space-y-6">
    
    <!-- Period Selector -->
    <div class="bg-surface rounded-xl border border-line p-3 flex flex-wrap gap-2">
        @foreach(['daily', 'weekly', 'monthly', '30days', 'all'] as $period)
            <button 
                wire:click="setPeriod('{{ $period }}')"
                class="px-3.5 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $currentPeriod === $period ? 'bg-accent text-surface' : 'bg-surface border border-line text-stone hover:bg-paper hover:text-ink' }}">
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

    <!-- Key Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Overall Score -->
        <div class="bg-surface rounded-xl border border-line p-5">
            <h3 class="text-xs font-medium text-stone mb-2">Overall score</h3>
            <p class="text-3xl font-semibold text-accent">{{ number_format($overallScore, 1) }}%</p>
            <p class="text-xs text-stone mt-1">From {{ $totalAssessments }} assessments</p>
            
            @if($bestScore > 0 && $worstScore > 0)
                <div class="mt-3 pt-2 border-t border-line text-xs text-stone">
                    Best: <span class="font-medium text-ink">{{ $bestScore }}%</span> | 
                    Worst: <span class="font-medium text-ink">{{ $worstScore }}%</span>
                </div>
            @endif
        </div>

        <!-- Mistakes -->
        <div class="bg-surface rounded-xl border border-line p-5">
            <h3 class="text-xs font-medium text-stone mb-2">Mistake resolution</h3>
            <div class="flex items-end justify-between mb-2">
                <p class="text-3xl font-semibold text-ink">
                    {{ $mistakeStats['resolved_mistakes'] }}/{{ $mistakeStats['total_mistakes'] }}
                </p>
                @if($mistakeStats['total_mistakes'] > 0)
                    <p class="text-xl font-semibold text-accent">
                        {{ number_format(($mistakeStats['resolved_mistakes'] / max(1, $mistakeStats['total_mistakes'])) * 100, 0) }}%
                    </p>
                @else
                    <p class="text-xl font-semibold text-correct">100%</p>
                @endif
            </div>
            <p class="text-xs text-stone">
                @if($mistakeStats['unresolved_mistakes'] > 0)
                    {{ $mistakeStats['unresolved_mistakes'] }} remaining to resolve
                @else
                    All mastered!
                @endif
            </p>
        </div>

        <!-- Questions Answered -->
        <div class="bg-surface rounded-xl border border-line p-5">
            <h3 class="text-xs font-medium text-stone mb-2">Practice activity</h3>
            <p class="text-3xl font-semibold text-ink">{{ number_format($questionsAnswered) }}</p>
            <p class="text-xs text-stone mt-1">Questions answered in last 7 days</p>
        </div>

        <!-- Benchmark Status -->
        <div class="bg-surface rounded-xl border border-line p-5">
            <h3 class="text-xs font-medium text-stone mb-2">Preparation level</h3>
            @if($overallScore >= 60)
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-correct mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xl font-semibold text-correct">Ready</p>
                </div>
                <p class="text-xs text-stone mt-1">Above 60% benchmark</p>
            @elseif($overallScore > 0)
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-stone mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xl font-semibold text-ink">In progress</p>
                </div>
                <p class="text-xs text-stone mt-1">{{ number_format(60 - $overallScore, 1) }}% to go</p>
            @else
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-stone mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xl font-semibold text-stone">Not started</p>
                </div>
                <p class="text-xs text-stone mt-1">Take first assessment</p>
            @endif
        </div>
    </div>

    <!-- Assessment Performance Trend Chart -->
    <div class="bg-surface rounded-xl border border-line overflow-hidden p-5 md:p-6">
        <h3 class="text-base font-medium text-ink mb-4">Assessment performance trend</h3>
        
        @if(count($assessmentHistory) > 1)
            <!-- Chart.js Canvas -->
            <div class="relative h-64 bg-paper/30 rounded-lg p-2 border border-line">
                <canvas id="trendChart"></canvas>
            </div>
            
            <!-- Score data table -->
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-line">
                    <thead class="bg-paper/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Duration</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Score</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-stone">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-surface divide-y divide-line">
                        @foreach(array_reverse($assessmentHistory) as $assessment)
                            <tr class="hover:bg-paper/30 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-ink">
                                    {{ date('M j, Y', strtotime($assessment['started_at'])) }}<br>
                                    <span class="text-xs text-stone">{{ date('g:i a', strtotime($assessment['started_at'])) }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-xs text-stone">
                                    {{ number_format($assessment['time_spent_seconds'] ?? 0, 0) }}s
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs font-medium rounded border border-line {{ $assessment['score'] >= 60 ? 'bg-correct-surface text-correct' : 'bg-wrong-surface text-wrong' }}">
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

        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-10 w-10 text-stone mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="text-sm font-medium text-ink">No assessment data yet</h3>
                <p class="text-xs text-stone mt-1">Complete your first assessment to see your progress over time.</p>
                <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-4 py-2 mt-4 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors">
                    Take assessment →
                </a>
            </div>
        @endif
    </div>

    <!-- Topic Performance Breakdown -->
    <div class="bg-surface rounded-xl border border-line overflow-hidden">
        <div class="px-5 py-4 border-b border-line bg-paper/30">
            <h3 class="text-base font-medium text-ink">Topic performance</h3>
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
                                        {{ $topic['attempts'] }} attempts
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <span class="text-xl font-semibold text-accent">{{ number_format($topic['average_score'], 1) }}%</span>
                                </div>
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

            <!-- Summary Stats -->
            <div class="px-5 py-4 bg-paper/30 border-t border-line grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-stone">Top topic</p>
                    <p class="text-sm font-medium text-ink">{{ $topicPerformance[0]['topic_name'] }}</p>
                    <p class="text-xs text-correct">{{ number_format($topicPerformance[0]['average_score'], 1) }}%</p>
                </div>
                <div>
                    <p class="text-xs text-stone">Average across topics</p>
                    <p class="text-base font-semibold text-ink">
                        {{ number_format(collect($topicPerformance)->avg('average_score'), 1) }}%
                    </p>
                </div>
                <div>
                    <p class="text-xs text-stone">Weak areas</p>
                    <p class="text-sm font-medium text-wrong">
                        {{ collect($topicPerformance)->where('average_score', '<', 60)->count() }} topics
                    </p>
                </div>
            </div>

        @else
            <div class="text-center py-12">
                <p class="text-xs text-stone">Start taking assessments to see topic-level breakdown</p>
            </div>
        @endif
    </div>

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

