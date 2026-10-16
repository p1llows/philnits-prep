<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    
    @if($this->getCurrentQuestion())
        <!-- Question Progress & Skip Header (matching mockup) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between text-xs sm:text-sm font-semibold text-stone">
                <span class="text-ink font-bold">
                    Question {{ $this->getCurrentQuestionNumber() }} of {{ $this->getTotalQuestions() }}
                </span>
                
                <button 
                    wire:click="skipQuestion" 
                    type="button" 
                    class="text-stone hover:text-accent font-medium text-xs transition-colors hover:underline"
                >
                    Skip
                </button>
            </div>

            <!-- Sleek Navy Progress Track -->
            <div class="w-full bg-[#E3DFD5] rounded-full h-2 overflow-hidden">
                <div 
                    class="bg-accent h-2 rounded-full transition-all duration-300 ease-out" 
                    style="width: {{ max(6, round(($this->getCurrentQuestionNumber() / max(1, $this->getTotalQuestions())) * 100)) }}%"
                ></div>
            </div>
        </div>

        <!-- Main Question Card (matching mockup) -->
        <div class="bg-surface rounded-3xl border border-[#E3DFD5] p-6 sm:p-8 shadow-xs space-y-6">
            
            <!-- Question Text -->
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-md bg-paper border border-[#E3DFD5] text-accent text-xs font-mono font-semibold mb-3">
                    {{ $this->getCurrentQuestion()['topic']['name'] ?? 'IT Passport Question' }}
                </span>
                <h2 class="text-base sm:text-lg font-bold text-ink leading-relaxed">
                    <x-question-text :text="$this->getCurrentQuestion()['question_text']" />
                </h2>

                @if(!empty($this->getCurrentQuestion()['image_path']))
                    <div class="mt-4 rounded-xl overflow-hidden border border-[#E3DFD5] bg-paper/50 flex justify-center p-2">
                        <img src="{{ asset('storage/' . $this->getCurrentQuestion()['image_path']) }}" alt="Question illustration" class="max-h-96 w-auto object-contain rounded-lg" />
                    </div>
                @endif

                @if(!empty($this->getCurrentQuestion()['code_block']))
                    <div class="mt-4 rounded-xl bg-gray-900 text-gray-100 p-4 font-mono text-xs md:text-sm overflow-x-auto border border-gray-800 shadow-inner">
                        <pre><code>{{ $this->getCurrentQuestion()['code_block'] }}</code></pre>
                    </div>
                @endif
            </div>

            <!-- Choices Radio Cards -->
            <div class="space-y-3">
                @php
                    $currentAnswer = $this->answers[$this->getCurrentQuestion()['id']] ?? null;
                @endphp

                @foreach($this->getCurrentQuestion()['choices'] as $choice)
                    @php
                        $isSelected = ($currentAnswer === $choice['code']);
                        $isCorrectChoice = ($choice['code'] === $this->getCurrentQuestion()['correct_answer_code']);
                        
                        // Style states
                        $cardClass = 'border-[#E3DFD5] bg-surface hover:bg-paper hover:border-accent/40';
                        $circleClass = 'border-[#CFCABD] bg-surface';
                        
                        if ($this->showingFeedback) {
                            if ($isCorrectChoice) {
                                $cardClass = 'border-correct/60 bg-correct-surface/40 text-correct';
                                $circleClass = 'border-correct bg-correct text-surface';
                            } elseif ($isSelected && !$isCorrectChoice) {
                                $cardClass = 'border-wrong/60 bg-wrong-surface/40 text-wrong';
                                $circleClass = 'border-wrong bg-wrong text-surface';
                            }
                        } elseif ($isSelected) {
                            $cardClass = 'border-accent bg-accent-tint/30 text-accent font-semibold ring-1 ring-accent';
                            $circleClass = 'border-accent bg-accent text-surface';
                        }
                    @endphp

                    <button
                        wire:click="selectAnswer('{{ $choice['code'] }}')"
                        @if($this->showingFeedback) disabled @endif
                        class="w-full text-left p-4 sm:p-4.5 rounded-2xl border transition-all duration-200 flex items-center space-x-3.5 group shadow-xs {{ $cardClass }}"
                    >
                        <!-- Radio Circle Indicator -->
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors {{ $circleClass }}">
                            @if($this->showingFeedback && $isCorrectChoice)
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($this->showingFeedback && $isSelected && !$isCorrectChoice)
                                <span class="text-white text-xs font-bold leading-none">&times;</span>
                            @elseif($isSelected)
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            @endif
                        </div>

                        <!-- Choice Text -->
                        <div class="text-xs sm:text-sm font-medium text-ink grow leading-normal">
                            <span class="font-bold mr-1.5 font-mono text-stone group-hover:text-ink">{{ $choice['code'] }}.</span>
                            <span>{{ $choice['text'] }}</span>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Feedback Box with Paxi Mascot -->
            @if($this->showingFeedback)
                <div class="relative p-4 sm:p-5 rounded-2xl border {{ $this->isCorrect ? 'bg-correct-surface/40 border-correct/40' : 'bg-wrong-surface/40 border-wrong/40' }} space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h4 class="font-bold text-sm sm:text-base {{ $this->isCorrect ? 'text-correct' : 'text-wrong' }}">
                                {{ $this->isCorrect ? 'Great job, that\'s correct!' : 'Not quite right — keep learning!' }}
                            </h4>
                            @if(!$this->isCorrect)
                                <p class="text-xs text-stone mt-0.5">
                                    The correct answer is <strong class="font-mono text-ink font-bold">{{ $this->getCurrentQuestion()['correct_answer_code'] }}</strong>.
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0">
                            @if($this->isCorrect)
                                <x-mascot variant="cheer" :size="56" class="w-14 h-14" />
                            @else
                                <x-mascot variant="oops" :size="56" class="w-14 h-14" />
                            @endif
                        </div>
                    </div>

                    @if($this->getCurrentQuestion()['explanation'])
                        <div class="pt-3 border-t border-[#E3DFD5]/80 text-xs text-stone leading-relaxed">
                            <span class="font-bold font-mono text-ink uppercase tracking-wider block mb-1">Explanation:</span>
                            {!! nl2br(e($this->getCurrentQuestion()['explanation'])) !!}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Bottom Navigation Controls (matching mockup: Previous & Next) -->
            <div class="flex items-center justify-between pt-4 border-t border-[#E3DFD5]">
                <button
                    wire:click="previousQuestion"
                    @if($this->currentIndex === 0) disabled @endif
                    type="button"
                    class="px-5 py-2.5 rounded-xl border border-[#D9D5C9] text-xs font-semibold {{ $this->currentIndex === 0 ? 'opacity-40 cursor-not-allowed text-stone bg-paper' : 'text-accent bg-surface hover:bg-paper' }} transition-colors shadow-xs"
                >
                    &larr; Previous
                </button>

                @if($this->showingFeedback || $this->currentIndex < count($this->questions) - 1)
                    <button
                        wire:click="nextQuestion"
                        type="button"
                        class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all"
                    >
                        <span>{{ $this->currentIndex < count($this->questions) - 1 ? 'Next' : 'Finish' }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </button>
                @endif
            </div>
        </div>

    @else
        <!-- No Questions or Empty State -->
        <div class="bg-surface border border-[#E3DFD5] p-8 sm:p-12 rounded-3xl text-center shadow-xs space-y-4">
            <div class="flex justify-center">
                <x-mascot variant="default" :size="120" />
            </div>
            <h3 class="text-lg font-bold text-ink">Practice Session Complete!</h3>
            <p class="text-xs sm:text-sm text-stone max-w-sm mx-auto">
                You've completed the available questions in this set.
            </p>
            <div class="pt-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl shadow-md transition-all">
                    <span>Back to dashboard</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    @endif
</div>
