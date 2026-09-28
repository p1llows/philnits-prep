<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Practice Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Practice Mode</h2>
                <p class="text-sm text-gray-600 mt-1">Immediate feedback - Learn as you go</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Progress</div>
                    <div class="text-lg font-semibold text-primary-700">{{ $this->getCurrentQuestionNumber() }}/{{ $this->getTotalQuestions() }}</div>
                </div>
                
                @if($this->correctCount > 0)
                    <div class="text-right">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Correct</div>
                        <div class="text-lg font-semibold text-green-600">{{ $this->correctCount }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div 
                class="bg-primary-600 h-3 rounded-full transition-all duration-300" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <div class="mt-2 flex items-center justify-end text-xs text-gray-500">
            {{ $this->getProgressPercentage() }}% completed
        </div>
    </div>

    <!-- Question Card -->
    @if($this->getCurrentQuestion())
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <!-- Question Section -->
            <div class="p-6 md:p-8 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center mb-4">
                    <span class="inline-block px-3 py-1 bg-primary-100 text-primary-700 text-sm font-medium rounded-full mr-3">
                        Question {{ $this->getCurrentQuestionNumber() }}
                    </span>
                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                        {{ $this->getCurrentQuestion()['topic']['name'] ?? 'Topic' }}
                    </span>
                </div>
                
                <h3 class="text-xl md:text-2xl font-semibold text-gray-900 leading-relaxed">
                    {{ $this->getCurrentQuestion()['question_text'] }}
                </h3>
            </div>

            <!-- Choices Section -->
            @if(!$this->showingFeedback)
                <!-- Answer Selection State -->
                <div class="p-6 md:p-8">
                    <div class="space-y-3">
                        @foreach($this->getCurrentQuestion()['choices'] as $choice)
                            <button
                                wire:click="selectAnswer('{{ $choice['code'] }}')"
                                class="w-full text-left p-4 border-2 border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all group focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                            >
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 text-gray-700 font-bold rounded-md flex items-center justify-center mr-4 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                                        {{ $choice['code'] }}
                                    </div>
                                    <div class="flex-grow text-gray-800">
                                        {{ $choice['text'] }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

            @else
                <!-- Feedback Display State -->
                <div class="p-6 md:p-8">
                    
                    <!-- Feedback Banner -->
                    <div class="mb-6 p-4 rounded-lg {{ $this->isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 {{ $this->isCorrect ? 'text-green-600' : 'text-red-600' }} mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                @if($this->isCorrect)
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                @endif
                            </svg>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-lg {{ $this->isCorrect ? 'text-green-800' : 'text-red-800' }}">
                                    {{ $this->feedbackMessage }}
                                </h4>
                                
                                @if(!$this->isCorrect)
                                    <p class="text-sm {{ $this->isCorrect ? 'text-green-700' : 'text-red-700' }} mt-1">
                                        The correct answer was <strong>{{ $this->getCurrentQuestion()['correct_answer_code'] }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Display (if incorrect) -->
                    @if(!$this->isCorrect)
                        <div class="mb-6">
                            <h5 class="font-medium text-gray-900 mb-3">Understanding the Correct Answer</h5>
                            
                            @foreach($this->getCurrentQuestion()['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentQuestion()['correct_answer_code'])
                                    <div class="p-4 bg-green-100 border-2 border-green-300 rounded-lg">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-8 h-8 bg-green-600 text-white font-bold rounded-md flex items-center justify-center mr-3">
                                                {{ $choice['code'] }}
                                            </div>
                                            <div class="text-gray-800">
                                                {{ $choice['text'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($this->getCurrentQuestion()['explanation'])
                                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                                    <h6 class="font-medium text-gray-900 mb-2">Explanation:</h6>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {!! nl2br(e($this->getCurrentQuestion()['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Navigation Controls -->
                    <div class="flex justify-between items-center pt-4">
                        <button
                            wire:click="previousQuestion"
                            disabled="{{ $this->currentIndex === 0 }}"
                            class="px-6 py-3 rounded-lg border-2 {{ $this->currentIndex === 0 ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-700 hover:bg-gray-50 font-medium' }} transition-colors"
                        >
                            ← Previous
                        </button>

                        <button
                            wire:click="nextQuestion"
                            class="px-8 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-sm"
                        >
                            @if($this->currentIndex < count($this->questions) - 1)
                                Next Question →
                            @else
                                Finish Practice ✓
                            @endif
                        </button>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- No Questions Available -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">No questions available</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        There are no published questions in this topic yet. Check back later or try a different topic.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Completion Modal (Alpine.js) -->
    <div x-data="{ show: false }" x-show="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Practice Complete!</h3>
            <p class="text-gray-600 mb-4">Great job! You've completed all available questions.</p>
            <div class="flex justify-end">
                <a href="{{ route('topics.index') }}" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    Back to Topics
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:navigated', () => {
    // Reset Alpine modal state on Livewire navigation
});
</script>
@endpush
