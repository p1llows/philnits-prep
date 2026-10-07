<div class="bg-surface rounded-xl border border-line overflow-hidden">
    
    <!-- Mistake Review Header -->
    <div class="p-5 border-b border-line bg-paper/30">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-medium text-ink">Mistake #{{ $this->getCurrentMistake()['id'] }}</h3>
            
            <div class="text-right">
                <div class="text-xs text-stone">Attempt</div>
                <div class="text-base font-semibold text-accent">{{ $this->getCurrentMistake()['attempt_number'] ?? 1 }}</div>
            </div>
        </div>

        @if($this->getCurrentMistake()['topic'])
            <div class="mb-3">
                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium bg-paper border border-line text-stone">
                    {{ $this->getCurrentMistake()['topic']['name'] }}
                </span>
            </div>
        @endif

        <!-- Progress Indicator -->
        <div class="w-full bg-line rounded-full h-2">
            <div 
                class="bg-accent h-2 rounded-full transition-all duration-300" 
                style="width: {{ (($this->getTotalMistakes() - $this->getUnresolvedCount()) / max(1, $this->getTotalMistakes())) * 100 }}%"
            ></div>
        </div>
        <div class="mt-1 text-xs text-stone flex justify-between">
            <span>{{ $this->getTotalMistakes() - $this->getUnresolvedCount() }} resolved</span>
            <span>{{ $this->getUnresolvedCount() }} remaining</span>
        </div>
    </div>

    @if($this->getCurrentMistake())
        <!-- Question Section -->
        <div class="p-6 md:p-8">
            <!-- Question Text (Source Serif 4) -->
            <h4 class="font-serif text-xl md:text-2xl font-normal text-ink mb-6 leading-relaxed">
                {{ $this->getCurrentMistake()['question_text'] }}
            </h4>

            @if(!$showingFeedback)
                <!-- Answer Selection State -->
                <div class="space-y-3 mb-6">
                    @foreach($this->getCurrentMistake()['question']['choices'] as $choice)
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

            @else
                <!-- Feedback Display State -->
                <div>
                    <!-- Feedback Banner -->
                    <div class="mb-6 p-4 rounded-lg border {{ $isCorrect ? 'bg-correct-surface border-line text-correct' : 'bg-wrong-surface border-line text-wrong' }}">
                        <div class="flex items-start">
                            <span class="mr-3 shrink-0 text-lg font-bold">
                                @if($isCorrect) ✓ @else ✕ @endif
                            </span>
                            
                            <div class="flex-1">
                                <h4 class="font-semibold text-base">
                                    {{ $isCorrect ? '✓ Correct!' : '✕ Incorrect' }}
                                </h4>
                                
                                @if(!$isCorrect)
                                    <p class="text-xs mt-1">
                                        Your answer: <strong>{{ $this->getCurrentMistake()['selected_answer_code'] }}</strong><br>
                                        The correct answer was <strong>{{ $this->getCurrentMistake()['correct_answer_code'] }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Explanation (if incorrect) -->
                    @if(!$isCorrect)
                        <div class="mb-6 p-4 bg-correct-surface border border-line rounded-lg text-correct">
                            <h5 class="font-medium text-sm mb-2">Understanding the correct answer</h5>
                            
                            @foreach($this->getCurrentMistake()['question']['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentMistake()['correct_answer_code'])
                                    <div class="bg-surface p-3 rounded border border-line mb-3 text-ink text-sm font-medium">
                                        {{ $choice['text'] }}
                                    </div>
                                @endif
                            @endforeach

                            @if($this->getCurrentMistake()['question']['explanation'])
                                <div class="bg-paper p-3 rounded border border-line text-xs text-stone">
                                    <h6 class="font-medium text-ink mb-1">Explanation:</h6>
                                    <p class="leading-relaxed">
                                        {!! nl2br(e($this->getCurrentMistake()['question']['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($isCorrect && $this->getUnresolvedCount() > 0)
                        <div class="text-center space-y-3">
                            <p class="text-xs text-correct font-medium">
                                ✓ Great! This question has been removed from your mistakes.
                            </p>
                            <button
                                wire:click="nextMistake"
                                class="px-6 py-2.5 bg-ink text-paper rounded-lg font-medium hover:bg-black transition-colors text-xs inline-flex items-center"
                            >
                                Next mistake →
                            </button>
                        </div>
                    @elseif($isCorrect && $this->getUnresolvedCount() === 0)
                        <div class="text-center bg-surface p-6 rounded-xl border border-line">
                            <svg class="mx-auto h-10 w-10 text-correct mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-base font-semibold text-ink mb-1">Excellent!</h3>
                            <p class="text-xs text-stone mb-4">You've mastered all your mistakes.</p>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-5 py-2.5 bg-ink text-paper rounded-lg hover:bg-black text-xs font-medium">
                                Back to dashboard
                            </a>
                        </div>
                    @else
                        <div class="flex justify-between items-center pt-4 border-t border-line">
                            <button
                                wire:click="previousMistake"
                                disabled="{{ true }}"
                                class="px-5 py-2.5 rounded-lg border border-line opacity-40 cursor-not-allowed text-stone bg-paper text-xs font-medium"
                            >
                                ← Previous
                            </button>

                            <button
                                wire:click="nextMistake"
                                class="px-6 py-2.5 bg-ink text-paper rounded-lg font-medium hover:bg-black transition-colors text-xs"
                            >
                                Next mistake →
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>

