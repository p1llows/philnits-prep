<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    
    <!-- Practice Header -->
    <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3.5">
                <div class="shrink-0 hidden sm:block">
                    <x-mascot variant="default" :size="56" label="Passy practice guide" />
                </div>
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-0.5 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs mb-2">
                        <span>PRACTICE MODE</span>
                    </div>
                    <h2 class="text-2xl font-serif font-bold text-ink">Topic Practice</h2>
                    <p class="text-xs text-stone mt-0.5">Immediate feedback — Learn through active recall</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <div class="text-xs font-mono text-stone uppercase">Progress</div>
                    <div class="text-base font-mono font-bold text-accent">{{ $this->getCurrentQuestionNumber() }}/{{ $this->getTotalQuestions() }}</div>
                </div>
                
                @if($this->correctCount > 0)
                    <div class="text-right">
                        <div class="text-xs font-mono text-stone uppercase">Correct</div>
                        <div class="text-base font-mono font-bold text-correct">{{ $this->correctCount }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-[#E3DFD5] rounded-full h-2.5 overflow-hidden">
            <div 
                class="bg-accent h-2.5 rounded-full transition-all duration-300" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <div class="mt-2 flex items-center justify-end text-xs font-mono text-stone">
            {{ $this->getProgressPercentage() }}% completed
        </div>
    </div>

    <!-- Question Card -->
    @if($this->getCurrentQuestion())
        <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
            
            <!-- Question Section -->
            <div class="p-6 md:p-8 border-b border-[#E3DFD5] bg-panel">
                <div class="flex items-center mb-4 space-x-2">
                    <span class="inline-flex items-center px-3 py-1 bg-surface border border-[#D9D5C9] text-accent text-xs font-mono font-bold rounded-full shadow-xs">
                        Question {{ $this->getCurrentQuestionNumber() }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 bg-paper border border-[#D9D5C9] text-stone text-xs font-mono rounded-full">
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
                                class="w-full text-left p-4 border border-[#E3DFD5] rounded-xl bg-surface hover:bg-accent-tint/40 hover:border-accent transition-all group focus:outline-none focus:ring-2 focus:ring-accent shadow-xs"
                            >
                                <div class="flex items-center">
                                    <div class="shrink-0 w-8 h-8 bg-paper border border-[#D9D5C9] text-accent font-mono font-bold rounded-lg flex items-center justify-center mr-3 group-hover:bg-accent group-hover:text-surface group-hover:border-accent transition-colors text-xs">
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
                    <div class="mb-6 p-5 rounded-xl border {{ $this->isCorrect ? 'bg-correct-surface/40 border-correct/40 text-correct' : 'bg-wrong-surface/40 border-wrong/40 text-wrong' }}">
                        <div class="flex items-center gap-4">
                            <div class="shrink-0">
                                @if($this->isCorrect)
                                    <x-mascot variant="passed" :size="48" />
                                @else
                                    <x-mascot variant="oops" :size="48" />
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-base">
                                    {{ $this->feedbackMessage }}
                                </h4>
                                
                                @if(!$this->isCorrect)
                                    <p class="text-xs mt-1">
                                        The correct answer was <strong class="font-mono font-bold">{{ $this->getCurrentQuestion()['correct_answer_code'] }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Display (if incorrect) -->
                    @if(!$this->isCorrect)
                        <div class="mb-6">
                            <h5 class="font-serif font-semibold text-ink text-sm mb-3">Understanding the correct answer</h5>
                            
                            @foreach($this->getCurrentQuestion()['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentQuestion()['correct_answer_code'])
                                    <div class="p-4 bg-correct-surface/40 border border-correct/40 rounded-xl text-correct">
                                        <div class="flex items-start">
                                            <div class="shrink-0 w-7 h-7 bg-correct text-surface font-mono font-bold rounded-lg flex items-center justify-center mr-3 text-xs">
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
                                <div class="mt-4 p-5 bg-paper/60 border border-[#E3DFD5] rounded-xl text-xs text-stone leading-relaxed">
                                    <h6 class="font-bold text-ink uppercase tracking-wider font-mono mb-1">Explanation:</h6>
                                    <p class="leading-relaxed">
                                        {!! nl2br(e($this->getCurrentQuestion()['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Navigation Controls -->
                    <div class="flex justify-between items-center pt-5 border-t border-[#E3DFD5]">
                        <button
                            wire:click="previousQuestion"
                            @if($this->currentIndex === 0) disabled @endif
                            class="px-5 py-2.5 rounded-xl border border-[#D9D5C9] text-xs font-medium {{ $this->currentIndex === 0 ? 'opacity-40 cursor-not-allowed text-stone bg-paper' : 'text-accent bg-surface hover:bg-paper' }} transition-colors shadow-xs"
                        >
                            ← Previous
                        </button>

                        <button
                            wire:click="nextQuestion"
                            class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
                        >
                            @if($this->currentIndex < count($this->questions) - 1)
                                <span>Next question →</span>
                            @else
                                <span>Finish practice ✓</span>
                            @endif
                        </button>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- No Questions Available -->
        <div class="bg-surface border border-[#CFCABD] p-6 rounded-2xl text-stone shadow-xs">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-stone mr-3 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-ink">No questions available</h3>
                    <p class="text-xs text-stone mt-1">
                        There are no published questions in this topic yet. Check back later or try a different topic.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

