<div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
    @if($this->getCurrentMistake())
        <!-- Mistake Review Header -->
        <div class="p-6 border-b border-[#E3DFD5] bg-panel">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-0.5 rounded-full border border-[#D9D5C9] bg-paper text-accent font-mono text-xs mb-1">
                        <span>MISTAKE REVIEW</span>
                    </div>
                    <h3 class="text-lg font-serif font-bold text-ink">Mistake #{{ $this->getCurrentMistake()['id'] }}</h3>
                </div>
                
                <div class="text-right">
                    <div class="text-xs font-mono text-stone uppercase">Attempt</div>
                    <div class="text-base font-mono font-bold text-accent">{{ $this->getCurrentMistake()['attempt_number'] ?? 1 }}</div>
                </div>
            </div>

            @if($this->getCurrentMistake()['topic'])
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-mono bg-paper border border-[#D9D5C9] text-stone">
                        {{ $this->getCurrentMistake()['topic']['name'] }}
                    </span>
                </div>
            @endif

            <!-- Progress Indicator -->
            <div class="w-full bg-[#E3DFD5] rounded-full h-2.5 overflow-hidden">
                <div 
                    class="bg-accent h-2.5 rounded-full transition-all duration-300" 
                    style="width: {{ (($this->getTotalMistakes() - $this->getUnresolvedCount()) / max(1, $this->getTotalMistakes())) * 100 }}%"
                ></div>
            </div>
            <div class="mt-2 text-xs font-mono text-stone flex justify-between">
                <span class="text-correct font-semibold">{{ $this->getTotalMistakes() - $this->getUnresolvedCount() }} resolved</span>
                <span class="text-wrong font-semibold">{{ $this->getUnresolvedCount() }} remaining</span>
            </div>
        </div>

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

                <!-- Paxi Companion Thinking Hint -->
                <div class="mt-4 flex items-center space-x-3 p-3.5 rounded-xl bg-paper border border-[#E3DFD5] text-xs">
                    <x-mascot variant="avatar" :size="32" class="w-8 h-8 rounded-full shrink-0 shadow-xs" label="Paxi companion" />
                    <span class="text-stone leading-relaxed">
                        Take your time! Think through why your previous choice 
                        @if($this->getCurrentMistake()['selected_answer_code'])
                            (<strong class="font-mono text-ink">{{ $this->getCurrentMistake()['selected_answer_code'] }}</strong>)
                        @endif
                        was incorrect before picking again.
                    </span>
                </div>

            @else
                <!-- Feedback Display State -->
                <div>
                    <!-- Feedback Banner with Paxi Companion Reaction -->
                    <div class="mb-6 p-5 rounded-2xl border {{ $isCorrect ? 'bg-correct-surface/40 border-correct/40 text-correct' : 'bg-wrong-surface/40 border-wrong/40 text-wrong' }}">
                        <div class="flex items-center space-x-4">
                            <div class="shrink-0">
                                @if($isCorrect)
                                    <x-mascot variant="passed" :size="48" class="w-12 h-12" label="Paxi passed" />
                                @else
                                    <x-mascot variant="oops" :size="48" class="w-12 h-12" label="Paxi oops" />
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-sm sm:text-base">
                                    {{ $isCorrect ? 'Paxi: Excellent work! That mistake is cleared!' : 'Paxi: Not quite, but mistakes are how we learn!' }}
                                </h4>
                                
                                @if(!$isCorrect)
                                    <p class="text-xs text-stone mt-1">
                                        Your previous answer was <strong class="font-mono font-bold text-ink">{{ $this->getCurrentMistake()['selected_answer_code'] }}</strong>.
                                        The correct answer is <strong class="font-mono font-bold text-correct">{{ $this->getCurrentMistake()['correct_answer_code'] }}</strong>.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Correct Choice Explanation (if incorrect) -->
                    @if(!$isCorrect)
                        <div class="mb-6 p-5 bg-correct-surface/30 border border-correct/30 rounded-xl text-correct">
                            <h5 class="font-serif font-semibold text-sm mb-2 text-ink">Understanding the correct answer</h5>
                            
                            @foreach($this->getCurrentMistake()['question']['choices'] as $choice)
                                @if($choice['code'] === $this->getCurrentMistake()['correct_answer_code'])
                                    <div class="bg-surface p-3.5 rounded-xl border border-[#E3DFD5] mb-3 text-ink text-sm font-medium shadow-xs">
                                        <span class="font-mono font-bold text-correct mr-2">{{ $choice['code'] }}:</span>
                                        {{ $choice['text'] }}
                                    </div>
                                @endif
                            @endforeach

                            @if($this->getCurrentMistake()['question']['explanation'])
                                <div class="bg-paper/80 p-4 rounded-xl border border-[#E3DFD5] text-xs text-stone leading-relaxed">
                                    <h6 class="font-mono font-bold text-ink uppercase tracking-wider mb-1">Explanation:</h6>
                                    <p class="leading-relaxed">
                                        {!! nl2br(e($this->getCurrentMistake()['question']['explanation'])) !!}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($isCorrect && $this->getUnresolvedCount() > 0)
                        <div class="text-center space-y-3">
                            <p class="text-xs font-mono text-correct font-semibold">
                                ✓ Great job! This question has been removed from your mistake bank.
                            </p>
                            <button
                                wire:click="nextMistake"
                                class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-xs"
                            >
                                <span>Next mistake →</span>
                            </button>
                        </div>
                    @elseif($isCorrect && $this->getUnresolvedCount() === 0)
                        <div class="text-center bg-surface p-8 rounded-2xl border border-[#CFCABD] shadow-xs">
                            <div class="mb-4 flex justify-center">
                                <x-mascot variant="passed" :size="120" />
                            </div>
                            <h3 class="text-lg font-serif font-bold text-ink mb-1">Excellent work!</h3>
                            <p class="text-xs text-stone mb-4">You have reviewed and cleared all recorded mistakes.</p>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-xs">
                                <span>Back to dashboard</span>
                            </a>
                        </div>
                    @else
                        <div class="flex justify-between items-center pt-5 border-t border-[#E3DFD5]">
                            <button
                                wire:click="previousMistake"
                                disabled="{{ true }}"
                                class="px-5 py-2.5 rounded-xl border border-[#D9D5C9] opacity-40 cursor-not-allowed text-stone bg-paper text-xs font-medium shadow-xs"
                            >
                                ← Previous
                            </button>

                            <button
                                wire:click="nextMistake"
                                class="inline-flex items-center space-x-2 px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-xs"
                            >
                                <span>Next mistake →</span>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @else
        <div class="p-8 sm:p-12 text-center bg-surface">
            @if($this->getResolvedCount() > 0 && $this->getUnresolvedCount() === 0)
                <div class="mb-4 flex justify-center">
                    <x-mascot variant="passed" :size="130" label="Paxi passed all mistakes" />
                </div>
                <h3 class="text-xl font-bold text-ink mb-1">Paxi: Excellent work!</h3>
                <p class="text-xs sm:text-sm text-stone max-w-sm mx-auto mb-6">
                    You have cleared all recorded mistakes from your bank. Your exam mastery just leveled up!
                </p>
            @else
                <div class="mb-4 flex justify-center">
                    <x-mascot variant="default" :size="110" label="Paxi companion" />
                </div>
                <h3 class="text-xl font-bold text-ink mb-1">Your Mistake Bank is empty!</h3>
                <p class="text-xs sm:text-sm text-stone max-w-sm mx-auto mb-6">
                    No active mistakes to review right now. Complete more study sessions or assessments and Paxi will collect any tricky questions here for you.
                </p>
            @endif
            <div class="flex items-center justify-center space-x-3">
                <a href="{{ route('topics.index') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-paper border border-[#D9D5C9] text-accent hover:bg-surface rounded-xl font-semibold text-xs shadow-xs transition-all">
                    <span>Study topics</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 rounded-xl font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all text-xs">
                    <span>Back to dashboard</span>
                </a>
            </div>
        </div>
    @endif
</div>
