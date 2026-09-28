<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    
    <!-- Mistake Review Header -->
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-orange-50">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Mistake #{{ $this->getCurrentMistake()['id'] }}</h3>
        
        <div class="flex items-center justify-between mb-4">
            @if($this->getCurrentMistake()['topic'])
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                      style="background-color: {{ $this->getCurrentMistake()['topic']['color'] }}20; color: {{ $this->getCurrentMistake()['topic']['color'] }}">
                    {{ $this->getCurrentMistake()['topic']['name'] }}
                </span>
            @endif
            
            <div class="text-right">
                <div class="text-xs text-gray-500 uppercase">Attempt</div>
                <div class="text-lg font-bold text-red-600">{{ $this->getCurrentMistake()['attempt_number'] ?? 1 }}</div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
                class="bg-red-600 h-2 rounded-full transition-all duration-300" 
                style="width: {{ (($this->getTotalMistakes() - $this->getUnresolvedCount()) / max(1, $this->getTotalMistakes())) * 100 }}%"
            ></div>
        </div>
        <div class="mt-1 text-xs text-gray-500 flex justify-between">
            <span>{{ $this->getTotalMistakes() - $this->getUnresolvedCount() }} resolved</span>
            <span>{{ $this->getUnresolvedCount() }} remaining</span>
        </div>
    </div>

    @if($this->getCurrentMistake())
        <!-- Question Section -->
        <div class="p-6 md:p-8">
            <h4 class="text-lg md:text-xl font-medium text-gray-900 mb-6 leading-relaxed">
                {{ $this->getCurrentMistake()['question_text'] }}
            </h4>

            @if(!$showingFeedback)
                <!-- Answer Selection State -->
                <div class="space-y-3 mb-6">
                    @foreach($this->getCurrentMistake()['question']['choices'] as $choice)
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

            @else
                <!-- Feedback Display State -->
                <div>
                    <!-- Feedback Banner -->
                    <div class="mb-6 p-4 rounded-lg {{ $isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 {{ $isCorrect ? 'text-green-600' : 'text-red-600' }} mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                @if($isCorrect)
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                @endif
                            </svg>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-lg {{ $isCorrect ? 'text-green-800' : 'text-red-800' }}">
                                    {{ $isCorrect ? '✓ Correct!' : '✗ Incorrect' }}
                                </h4>
                                
                                @if(!$isCorrect)
                                    <p class="text-sm text-red-700 mt-1">
                                        Your answer: <strong>{{ $this->getCurrentMistake()['selected_answer_code'] }}</strong><br>
                                        The correct answer was <strong>{{ $this->getCurrentMistake()['correct_answer_code'] }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Explanation (if incorrect) -->
                    @if(!$isCorrect)
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 rounded-r-lg p-4">
                            <h5 class="font-medium text-gray-900 mb-2">Understanding the Correct Answer</h5>
                            
                            @foreach($this->getCurrentMistake()['question']['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentMistake()['correct_answer_code'])
                                    <div class="bg-white p-3 rounded border border-green-200 mb-3">
                                        <div class="font-medium text-gray-900">{{ $choice['text'] }}</div>
                                    </div>
                                @endif
                            @endforeach

                            @if($this->getCurrentMistake()['question']['explanation'])
                                <div class="bg-yellow-50 p-3 rounded">
                                    <h6 class="font-medium text-gray-900 mb-1">Explanation:</h6>
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        {!! nl2br(e($this->getCurrentMistake()['question']['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($isCorrect && $this->getUnresolvedCount() > 0)
                        <div class="text-center">
                            <p class="text-sm text-green-700 mb-3">
                                ✓ Great! This question has been removed from your mistakes.
                            </p>
                            <button
                                wire:click="nextMistake"
                                class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors shadow-sm inline-flex items-center"
                            >
                                Next Mistake →
                            </button>
                        </div>
                    @elseif($isCorrect && $this->getUnresolvedCount() === 0)
                        <div class="text-center bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border border-green-200">
                            <svg class="mx-auto h-12 w-12 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Excellent!</h3>
                            <p class="text-gray-700 mb-4">You've mastered all your mistakes.</p>
                            <a href="{{ route('dashboard') }}" class="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                Back to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="flex justify-between items-center pt-4">
                            <button
                                wire:click="previousMistake"
                                disabled="{{ true }}"
                                class="px-6 py-3 rounded-lg border-2 border-gray-200 text-gray-300 cursor-not-allowed font-medium transition-colors"
                            >
                                ← Previous
                            </button>

                            <button
                                wire:click="nextMistake"
                                class="px-8 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-sm"
                            >
                                Next Mistake →
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
