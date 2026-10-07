<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-2">
    <!-- Progress & Quick Jump Header -->
    <div class="bg-surface/90 backdrop-blur-md rounded-2xl border border-line p-5 md:p-6 shadow-xs space-y-5">
        <!-- Top Status Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center space-x-2.5">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-accent"></span>
                </span>
                <span class="text-xs font-semibold uppercase tracking-wider text-accent bg-accent-tint px-2.5 py-1 rounded-full border border-accent/15">
                    IP Passport Assessment
                </span>
            </div>

            <div class="flex items-center space-x-4 text-xs">
                <span class="text-stone">
                    Question <strong class="text-ink font-semibold">{{ $this->getQuestionNumber() }}</strong> of <strong class="text-ink font-semibold">{{ $this->getTotalQuestions() }}</strong>
                </span>
                <span class="px-2.5 py-1 rounded-full bg-paper border border-line font-medium text-ink">
                    {{ $this->getProgressPercentage() }}% Completed
                </span>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-paper border border-line/60 rounded-full h-2.5 overflow-hidden p-0.5">
            <div 
                class="bg-gradient-to-r from-accent via-accent-600 to-accent h-full rounded-full transition-all duration-300 shadow-xs" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <!-- Quick Jump Navigation Grid -->
        <div class="pt-2 border-t border-line/40">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-stone">Question Palette</span>
                <div class="flex items-center space-x-4 text-xs text-stone">
                    <span class="flex items-center"><span class="w-2.5 h-2.5 inline-block bg-surface border border-line rounded mr-1.5"></span>Unanswered</span>
                    <span class="flex items-center"><span class="w-2.5 h-2.5 inline-block bg-accent-tint border border-accent/30 rounded mr-1.5"></span>Answered</span>
                    <span class="flex items-center"><span class="w-2.5 h-2.5 inline-block bg-accent rounded mr-1.5"></span>Current</span>
                </div>
            </div>

            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                @foreach($questions as $index => $question)
                    @php
                        $isCurrent = ($index === $currentQuestionIndex);
                        $isAnswered = isset($answers[$question['id']]);
                    @endphp
                    <button 
                        wire:click="navigateTo({{ $index }})"
                        class="h-9 text-xs rounded-xl transition-all duration-150 font-medium flex flex-col items-center justify-center border relative {{ $isCurrent ? 'bg-accent text-surface border-accent shadow-sm ring-2 ring-accent/20 scale-105 font-bold' : ($isAnswered ? 'bg-accent-tint text-accent border-accent/30 font-medium hover:bg-accent-tint/80' : 'bg-surface text-stone border-line hover:bg-paper hover:text-ink') }}"
                    >
                        <span>{{ $index + 1 }}</span>
                        @if($isAnswered && !$isCurrent)
                            <span class="absolute bottom-1 w-1 h-1 rounded-full bg-accent"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Question Card -->
    @if($currentQuestion = $this->getCurrentQuestion())
        <div class="bg-surface rounded-2xl border border-line p-6 md:p-8 shadow-xs relative overflow-hidden transition-all duration-200">
            <!-- Background Watermark Badge -->
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-accent-tint/30 rounded-full blur-xl pointer-events-none"></div>

            <!-- Header Badge -->
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs font-semibold text-stone bg-paper px-3 py-1 rounded-lg border border-line">
                    Question {{ $this->getQuestionNumber() }}
                </span>
                <span class="text-xs text-stone">
                    Single Choice
                </span>
            </div>

            <!-- Question Text -->
            <h2 class="font-serif text-lg md:text-xl text-ink mb-8 leading-relaxed font-normal">
                {{ $currentQuestion['question_text'] }}
            </h2>

            <!-- Choices Grid -->
            <div class="space-y-3">
                @foreach($currentQuestion['choices'] as $choice)
                    @php
                        $selectedAnswer = $answers[$currentQuestion['id']] ?? null;
                        $isSelected = ($selectedAnswer === $choice['code']);
                    @endphp
                    <label class="block cursor-pointer group">
                        <input 
                            type="radio" 
                            name="selected_answer_{{ $currentQuestion['id'] }}"
                            value="{{ $choice['code'] }}"
                            {{ $isSelected ? 'checked' : '' }}
                            wire:click="selectAnswer({{ $currentQuestion['id'] }}, '{{ $choice['code'] }}')"
                            class="sr-only peer"
                        />
                        <div class="flex items-center p-4 border rounded-xl transition-all duration-150 text-ink bg-surface group-hover:bg-paper/70 group-hover:border-accent/40 group-hover:shadow-xs peer-checked:border-accent peer-checked:bg-accent-tint/60 peer-checked:text-accent peer-checked:shadow-xs">
                            <div class="shrink-0 w-8 h-8 bg-paper border border-line text-stone font-bold rounded-lg flex items-center justify-center mr-3.5 text-xs transition-colors peer-checked:bg-accent peer-checked:text-surface peer-checked:border-accent group-hover:border-accent/50">
                                {{ $choice['code'] }}
                            </div>
                            <div class="grow text-sm font-sans leading-snug">
                                {{ $choice['text'] }}
                            </div>
                            <div class="ml-3 shrink-0 opacity-0 peer-checked:opacity-100 transition-opacity">
                                <div class="w-5 h-5 rounded-full bg-accent text-surface flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-surface border border-line p-8 rounded-2xl text-stone text-center shadow-xs">
            <svg class="mx-auto h-10 w-10 text-stone/60 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm text-stone font-medium">
                No questions available for this assessment. Please contact your administrator.
            </p>
        </div>
    @endif

    <!-- Navigation & Action Controls -->
    @if($this->getCurrentQuestion())
        <div class="flex items-center justify-between pt-2">
            <button
                wire:click="previousQuestion"
                {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}
                class="px-5 py-2.5 rounded-xl border border-line text-xs font-medium transition-all duration-150 flex items-center space-x-2 {{ $currentQuestionIndex === 0 ? 'opacity-40 cursor-not-allowed text-stone bg-paper' : 'text-ink bg-surface hover:bg-paper hover:border-line shadow-xs' }}"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Previous</span>
            </button>

            <div class="hidden sm:flex items-center space-x-2 text-xs text-stone bg-surface px-3 py-1.5 rounded-full border border-line">
                <span class="w-2 h-2 rounded-full bg-correct inline-block"></span>
                <span>{{ count($answers) }} of {{ count($questions) }} Answered</span>
            </div>

            @if($currentQuestionIndex < count($questions) - 1)
                <button
                    wire:click="nextQuestion"
                    class="px-6 py-2.5 bg-accent text-surface hover:bg-accent/90 text-xs font-semibold rounded-xl transition-all duration-150 shadow-xs flex items-center space-x-2"
                >
                    <span>Next</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @else
                <button
                    wire:click="submitAssessment"
                    {{ count($answers) === 0 ? 'disabled title="Please answer at least one question"' : '' }}
                    class="px-6 py-2.5 bg-gradient-to-r from-accent to-ink text-surface hover:brightness-110 text-xs font-semibold rounded-xl transition-all duration-150 shadow-md flex items-center space-x-2 {{ count($answers) === 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Submit Assessment</span>
                </button>
            @endif
        </div>
    @endif

    <!-- Submission Confirmation Modal -->
    <div 
        x-data="{ showModal: false }"
        x-show="showModal"
        class="fixed inset-0 bg-ink/50 backdrop-blur-xs flex items-center justify-center z-50 p-4"
        style="display: none;"
    >
        <div class="bg-surface rounded-2xl border border-line p-6 max-w-md w-full shadow-lg">
            <h3 class="text-lg font-semibold text-ink mb-2">Submit Assessment?</h3>
            <p class="text-xs text-stone mb-6 leading-relaxed">
                You have answered <strong class="text-ink font-semibold">{{ count($answers) }}</strong> out of {{ count($questions) }} questions.
                
                @if(count($answers) < count($questions))
                    <br><br>
                    <span class="text-wrong font-medium bg-wrong-surface/60 px-3 py-1.5 rounded-lg border border-wrong/20 inline-block">
                        ⚠️ You still have {{ $this->getUnansweredCount() }} unanswered question(s). Do you want to submit anyway?
                    </span>
                @endif
            </p>

            <div class="flex space-x-3 justify-end">
                <button 
                    wire:click="$dispatch('close-modal')"
                    class="px-4 py-2 border border-line bg-surface rounded-xl text-stone hover:text-ink hover:bg-paper text-xs font-medium transition-colors"
                >
                    Continue Exam
                </button>
                <button
                    onclick="document.querySelector('[wire\\:click=\`submitAssessment\']').click()"
                    class="px-4 py-2 bg-accent text-surface rounded-xl hover:bg-accent/90 text-xs font-semibold shadow-xs transition-colors"
                >
                    Yes, Submit
                </button>
            </div>
        </div>
    </div>
</div>

@script
<script>
$wire.on('answer-selected', () => {
    // Answer selection hook
});
</script>
@endscript
