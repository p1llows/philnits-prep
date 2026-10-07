<div class="space-y-6">
    
    <!-- Period Selector -->
    <div class="bg-white rounded-lg shadow-sm p-4 flex space-x-2">
        @foreach(['daily', 'weekly', 'monthly', '30days', 'all'] as $period)
            <button 
                wire:click="setPeriod('{{ $period }}')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentPeriod === $period ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                @switch($period)
                    @case('daily') Today @break
                    @case('weekly') This Week @break
                    @case('monthly') This Month @break
                    @case('30days') Last 30 Days @break
                    @case('all') All Time @break
                @endswitch
            </button>
        @endforeach
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Overall Score -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-l-4 border-blue-500">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Overall Score</h3>
            <p class="text-3xl font-bold text-blue-900">{{ number_format($overallScore, 1) }}%</p>
            <p class="text-xs text-gray-600 mt-1">From {{ $totalAssessments }} assessments</p>
            
            @if($bestScore > 0 && $worstScore > 0)
                <div class="mt-2 text-xs text-gray-600">
                    Best: <span class="font-semibold">{{ $bestScore }}%</span> | 
                    Worst: <span class="font-semibold">{{ $worstScore }}%</span>
                </div>
            @endif
        </div>

        <!-- Mistakes -->
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-6 border-l-4 border-red-500">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Mistake Resolution</h3>
            <div class="flex items-end justify-between mb-2">
                <p class="text-3xl font-bold text-red-900">
                    {{ $mistakeStats['resolved_mistakes'] }}/{{ $mistakeStats['total_mistakes'] }}
                </p>
                @if($mistakeStats['total_mistakes'] > 0)
                    <p class="text-2xl font-bold text-red-400">
                        {{ number_format(($mistakeStats['resolved_mistakes'] / max(1, $mistakeStats['total_mistakes'])) * 100, 0) }}%
                    </p>
                @else
                    <p class="text-2xl font-bold text-green-400">100%</p>
                @endif
            </div>
            <p class="text-xs text-gray-600">
                @if($mistakeStats['unresolved_mistakes'] > 0)
                    {{ $mistakeStats['unresolved_mistakes'] }} remaining to resolve
                @else
                    All mastered! 🎉
                @endif
            </p>
        </div>

        <!-- Questions Answered -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border-l-4 border-green-500">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Practice Activity</h3>
            <p class="text-3xl font-bold text-green-900">{{ number_format($questionsAnswered) }}</p>
            <p class="text-xs text-gray-600 mt-1">Questions answered in last 7 days</p>
        </div>

        <!-- Benchmark Status -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border-l-4 {{ $overallScore >= 60 ? 'border-green-500' : 'border-yellow-500' }}">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Preparation Level</h3>
            @if($overallScore >= 60)
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-2xl font-bold text-green-800">Ready</p>
                </div>
                <p class="text-xs text-gray-600 mt-1">Above 60% benchmark</p>
            @elseif($overallScore > 0)
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-2xl font-bold text-yellow-800">In Progress</p>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ number_format(60 - $overallScore, 1) }}% to go</p>
            @else
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-2xl font-bold text-gray-800">Not Started</p>
                </div>
                <p class="text-xs text-gray-600 mt-1">Take first assessment</p>
            @endif
        </div>
    </div>

    <!-- Assessment History Chart Placeholder -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assessment Performance Trend</h3>
        
        @if(count($assessmentHistory) > 1)
            <!-- Chart.js Integration would go here -->
            <div class="relative h-64 bg-gray-50 rounded-lg">
                <canvas id="trendChart"></canvas>
                
                <!-- Fallback: Text-based trend display -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h2v16"/>
                        </svg>
                        <p class="text-sm">Interactive chart visualization</p>
                        <p class="text-xs">Enable JavaScript for graph display</p>
                    </div>
                </div>
            </div>
            
            <!-- Score data table -->
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(array_reverse($assessmentHistory) as $assessment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ date('M j, Y', strtotime($assessment['started_at'])) }}<br>
                                    <span class="text-xs text-gray-500">{{ date('g:i a', strtotime($assessment['started_at'])) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($assessment['time_spent_seconds'] ?? 0, 0) }}s
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                        {{ $assessment['score'] >= 60 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ number_format($assessment['score'], 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $assessment['status'])) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">No assessment data yet</h3>
                <p class="text-gray-500 mt-1">Complete your first assessment to see your progress over time.</p>
                <a href="{{ route('assessments.index') }}" class="inline-block mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Take Assessment →
                </a>
            </div>
        @endif
    </div>

    <!-- Topic Performance Breakdown -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Topic Performance</h3>
            <p class="text-sm text-gray-600 mt-1">Your strongest and weakest topics based on assessment performance</p>
        </div>

        @if(count($topicPerformance) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($topicPerformance as $idx => $topic)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $topic['topic_name'] }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $topic['attempts'] }} attempts
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <span class="text-2xl font-bold text-gray-900">{{ number_format($topic['average_score'], 1) }}%</span>
                                    @if($topic['average_score'] >= 80)
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                        </svg>
                                    @elseif($topic['average_score'] >= 60)
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600">{{ $topic['correct_answers'] }}/{{ $topic['total_questions'] }} correct</p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div 
                                class="h-2 rounded-full {{ $topic['average_score'] >= 80 ? 'bg-green-500' : ($topic['average_score'] >= 60 ? 'bg-yellow-500' : 'bg-red-500') }} transition-all duration-500"
                                style="width: {{ $topic['average_score'] }}%"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Stats -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Top Topic</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $topicPerformance[0]['topic_name'] }}</p>
                    <p class="text-xs text-green-600">{{ number_format($topicPerformance[0]['average_score'], 1) }}%</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Average Across Topics</p>
                    <p class="text-lg font-bold text-gray-900">
                        {{ number_format(collect($topicPerformance)->avg('average_score'), 1) }}%
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Weak Areas</p>
                    <p class="text-sm font-semibold text-red-700">
                        {{ collect($topicPerformance)->where('average_score', '<', 60)->count() }} topics
                    </p>
                </div>
            </div>

        @else
            <div class="text-center py-12">
                <p class="text-gray-500">Start taking assessments to see topic-level breakdown</p>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:navigated', () => {
    // Initialize Chart.js if available
    const canvas = document.getElementById('trendChart');
    if (canvas && @json($assessmentHistory)) {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: @json($trendGraphData['labels']),
                datasets: [{
                    label: 'Assessment Score (%)',
                    data: @json($trendGraphData['scores']),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Score (%)'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
