<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    
    <!-- Practice Header -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-semibold text-ink">Practice mode</h2>
                <p class="text-xs text-stone mt-0.5">Immediate feedback — Learn as you go</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-xs text-stone">Progress</div>
                    <div class="text-base font-semibold text-accent">{{ $this->getCurrentQuestionNumber() }}/{{ $this->getTotalQuestions() }}</div>
                </div>
                
                @if($this->correctCount > 0)
                    <div class="text-right">
                        <div class="text-xs text-stone">Correct</div>
                        <div class="text-base font-semibold text-correct">{{ $this->correctCount }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-line rounded-full h-2">
            <div 
                class="bg-accent h-2 rounded-full transition-all duration-300" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <div class="mt-2 flex items-center justify-end text-xs text-stone">
            {{ $this->getProgressPercentage() }}% completed
        </div>
    </div>

    <!-- Question Card -->
    @if($this->getCurrentQuestion())
        <div class="bg-surface rounded-xl border border-line overflow-hidden">
            
            <!-- Question Section -->
            <div class="p-6 md:p-8 border-b border-line bg-paper/30">
                <div class="flex items-center mb-4 space-x-2">
                    <span class="inline-block px-2.5 py-1 bg-accent-tint text-accent text-xs font-medium rounded-lg">
                        Question {{ $this->getCurrentQuestionNumber() }}
                    </span>
                    <span class="inline-block px-2.5 py-1 bg-paper border border-line text-stone text-xs font-medium rounded-lg">
                        {{ $this->getCurrentQuestion()['topic']['name'] ?? 'Topic' }}
                    </span>
                </div>
                
                <!-- Question Text (Source Serif 4) -->
                <h3 class="font-serif text-xl md:text-2xl text-ink leading-relaxed font-normal">
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
                                class="w-full text-left p-4 border border-line rounded-lg hover:bg-accent-tint hover:border-accent transition-all group focus:outline-none focus:ring-2 focus:ring-accent"
                            >
                                <div class="flex items-center">
                                    <div class="shrink-0 w-8 h-8 bg-paper border border-line text-stone font-bold rounded-md flex items-center justify-center mr-3 group-hover:bg-accent group-hover:text-surface group-hover:border-accent transition-colors text-xs">
                                        {{ $choice['code'] }}
                                    </div>
                                    <div class="grow text-ink text-sm font-sans">
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
                    <div class="mb-6 p-4 rounded-lg border {{ $this->isCorrect ? 'bg-correct-surface border-line text-correct' : 'bg-wrong-surface border-line text-wrong' }}">
                        <div class="flex items-start">
                            <span class="mr-3 shrink-0 text-lg font-bold">
                                @if($this->isCorrect) ✓ @else ✕ @endif
                            </span>
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-base">
                                    {{ $this->feedbackMessage }}
                                </h4>
                                
                                @if(!$this->isCorrect)
                                    <p class="text-xs mt-1">
                                        The correct answer was <strong class="font-bold">{{ $this->getCurrentQuestion()['correct_answer_code'] }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Display (if incorrect) -->
                    @if(!$this->isCorrect)
                        <div class="mb-6">
                            <h5 class="font-medium text-ink text-sm mb-3">Understanding the correct answer</h5>
                            
                            @foreach($this->getCurrentQuestion()['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentQuestion()['correct_answer_code'])
                                    <div class="p-4 bg-correct-surface border border-line rounded-lg text-correct">
                                        <div class="flex items-start">
                                            <div class="shrink-0 w-7 h-7 bg-correct text-surface font-bold rounded flex items-center justify-center mr-3 text-xs">
                                                ✓
                                            </div>
                                            <div class="text-sm font-medium">
                                                {{ $choice['text'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($this->getCurrentQuestion()['explanation'])
                                <div class="mt-4 p-4 bg-paper border border-line rounded-lg text-xs text-stone">
                                    <h6 class="font-medium text-ink mb-1">Explanation:</h6>
                                    <p class="leading-relaxed">
                                        {!! nl2br(e($this->getCurrentQuestion()['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Navigation Controls -->
                    <div class="flex justify-between items-center pt-4 border-t border-line">
                        <button
                            wire:click="previousQuestion"
                            @if($this->currentIndex === 0) disabled @endif
                            class="px-5 py-2.5 rounded-lg border border-line text-xs font-medium {{ $this->currentIndex === 0 ? 'opacity-40 cursor-not-allowed text-stone bg-paper' : 'text-ink bg-surface hover:bg-paper' }} transition-colors"
                        >
                            ← Previous
                        </button>

                        <button
                            wire:click="nextQuestion"
                            class="px-6 py-2.5 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors"
                        >
                            @if($this->currentIndex < count($this->questions) - 1)
                                Next question →
                            @else
                                Finish practice ✓
                            @endif
                        </button>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- No Questions Available -->
        <div class="bg-surface border border-line p-5 rounded-xl text-stone">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-stone mr-3 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-ink">No questions available</h3>
                    <p class="text-xs text-stone mt-1">
                        There are no published questions in this topic yet. Check back later or try a different topic.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

