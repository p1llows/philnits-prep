<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Score Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 md:p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Your Score</h3>
                        
                        @php
                            $score = $assessment->score_percentage ?? 0;
                            $isAboveBenchmark = $score >= 60;
                        @endphp
                        
                        <div class="flex items-baseline justify-center">
                            <span class="text-7xl font-bold text-primary-700">{{ number_format($score, 1) }}</span>
                            <span class="text-2xl text-gray-600 ml-2">%</span>
                        </div>
                        
                        <div class="mt-4 flex justify-center items-center">
                            @if($isAboveBenchmark)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Above Preparation Benchmark
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Below Preparation Benchmark
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-700">{{ $assessment->correct_count }}</div>
                            <div class="text-sm text-green-600 mt-1">Correct</div>
                        </div>
                        
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-700">{{ $assessment->incorrect_count }}</div>
                            <div class="text-sm text-red-600 mt-1">Incorrect</div>
                        </div>
                        
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-700">{{ $assessment->total_questions }}</div>
                            <div class="text-sm text-blue-600 mt-1">Total Questions</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-700">{{ floor($assessment->time_spent_seconds / 60) }}m</div>
                            <div class="text-sm text-purple-600 mt-1">Time Spent</div>
                        </div>
                    </div>

                    <!-- Benchmark Comparison -->
                    <hr class="mb-6">
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-lg font-medium text-gray-700">Preparation Benchmark</span>
                        </div>
                        <p class="text-gray-600 mb-2">
                            Your score of <strong>{{ number_format($score, 1) }}%</strong> is 
                            @if($isAboveBenchmark)
                                <span class="text-green-600">above</span>
                            @else
                                <span class="text-orange-600">below</span>
                            @endif
                            the product's preparation benchmark of <strong>60%</strong>.
                        </p>
                        <p class="text-xs text-gray-500">
                            Note: This benchmark is for internal preparation tracking and should not be considered a guarantee of official examination results.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Topic Performance -->
            @if(count($topicPerformance) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                    <div class="p-6 md:p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Topic Performance Breakdown</h3>
                        
                        <div class="space-y-4">
                            @foreach($topicPerformance as $perf)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-4 h-4 rounded mr-2" style="background-color: {{ $perf['color'] }}"></div>
                                            <span class="font-medium text-gray-900">{{ $perf['topic_name'] }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900">{{ $perf['percentage'] }}%</span>
                                            <span class="text-sm text-gray-600 ml-2">({{ $perf['correct'] }}/{{ $perf['total'] }})</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div 
                                            class="h-2.5 rounded-full transition-all duration-300 {{ $perf['percentage'] >= 60 ? 'bg-green-500' : ($perf['percentage'] >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                            style="width: {{ $perf['percentage'] }}%"
                                        ></div>
                                    </div>
                                    
                                    @if($perf['percentage'] < 50)
                                        <div class="mt-2 text-sm text-orange-600">
                                            ⚠️ This topic needs improvement
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Weak Areas & Recommendations -->
            @if($weakAreas->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                    <div class="p-6 md:p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Focus Areas</h3>
                        <p class="text-gray-600 mb-6">
                            Based on your performance, these topics are showing weaker understanding. We recommend reviewing these areas first.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($weakAreas as $area)
                                <div class="border-l-4 border-orange-500 bg-orange-50 p-4 rounded-r-lg">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $area['topic_name'] }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $area['correct'] }} of {{ $area['total'] }} correct ({{ $area['percentage'] }}%)
                                            </p>
                                        </div>
                                        <button 
                                            onclick="window.location.href='/topics/{{ $area['topic_id'] }}'"
                                            class="px-3 py-1 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700"
                                        >
                                            Review Now
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recommended Actions -->
            @if($recommendedTopics->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                    <div class="p-6 md:p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recommended Next Steps</h3>
                        
                        <div class="space-y-3">
                            @foreach($recommendedTopics as $idx => $topic)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-primary-50 to-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold mr-3">
                                            {{ $idx + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $topic['topic_name'] }}</p>
                                            <p class="text-sm text-gray-600">Practice questions in this topic</p>
                                        </div>
                                    </div>
                                    <button 
                                        onclick="window.location.href='/topics/{{ $topic['topic_id'] }}'"
                                        class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 text-sm"
                                    >
                                        Practice →
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    ← Back to Dashboard
                </a>
                
                <a href="{{ route('assessment.index') }}" 
                   class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                    Take Another Assessment →
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
