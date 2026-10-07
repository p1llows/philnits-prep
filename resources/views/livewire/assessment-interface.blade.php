<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Progress Header -->
    <div>
        <div class="flex justify-between items-center mb-2 text-xs text-stone">
            <span class="font-medium text-ink">Question {{ $this->getQuestionNumber() }} of {{ $this->getTotalQuestions() }}</span>
            <span>{{ $this->getProgressPercentage() }}% completed</span>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-line rounded-full h-2">
            <div 
                class="bg-accent h-2 rounded-full transition-all duration-300" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <!-- Navigation Grid (for quick jump) -->
        <div class="mt-6">
            <div class="grid grid-cols-10 gap-2">
                @foreach($questions as $index => $question)
                    @php
                        $isCurrent = ($index === $currentQuestionIndex);
                        $isAnswered = isset($answers[$question['id']]);
                    @endphp
                    <button 
                        wire:click="navigateTo({{ $index }})"
                        class="p-2 text-xs rounded-lg transition-colors font-medium border {{ $isCurrent ? 'bg-accent text-surface border-accent' : ($isAnswered ? 'bg-accent-tint text-accent border-line' : 'bg-surface text-stone border-line hover:bg-paper hover:text-ink') }}"
                    >
                        {{ $index + 1 }}
                        @if($isAnswered)
                            <svg class="w-3 h-3 mx-auto mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>
            
            <div class="mt-3 flex items-center justify-end space-x-4 text-xs text-stone">
                <span class="flex items-center"><span class="w-3 h-3 inline-block bg-surface border border-line rounded mr-1.5"></span>Unanswered</span>
                <span class="flex items-center"><span class="w-3 h-3 inline-block bg-accent-tint border border-line rounded mr-1.5"></span>Answered</span>
                <span class="flex items-center"><span class="w-3 h-3 inline-block bg-accent rounded mr-1.5"></span>Current</span>
            </div>
        </div>
    </div>

    <!-- Question Card -->
    @if($currentQuestion = $this->getCurrentQuestion())
        <div class="bg-surface rounded-xl border border-line p-6 md:p-8">
            <!-- Question Text (Source Serif 4) -->
            <h2 class="font-serif text-xl md:text-2xl font-normal text-ink mb-6 leading-relaxed">
                {{ $currentQuestion['question_text'] }}
            </h2>

            <!-- Choices -->
            <div class="space-y-3">
                @foreach($currentQuestion['choices'] as $choice)
                    @php
                        $selectedAnswer = $answers[$currentQuestion['id']] ?? null;
                        $isSelected = ($selectedAnswer === $choice['code']);
                    @endphp
                    <label 
                        class="block cursor-pointer"
                    >
                        <input 
                            type="radio" 
                            name="selected_answer_{{ $currentQuestion['id'] }}"
                            value="{{ $choice['code'] }}"
                            {{ $isSelected ? 'checked' : '' }}
                            wire:click="selectAnswer({{ $currentQuestion['id'] }}, '{{ $choice['code'] }}')"
                            class="sr-only peer"
                        />
                        <div class="flex items-center p-4 border border-line rounded-lg transition-all text-ink hover:bg-paper peer-checked:border-accent peer-checked:bg-accent-tint peer-checked:text-accent">
                            <div class="shrink-0 w-8 h-8 bg-paper border border-line text-stone font-bold rounded-md flex items-center justify-center mr-3 text-xs peer-checked:bg-accent peer-checked:text-surface peer-checked:border-accent">
                                {{ $choice['code'] }}
                            </div>
                            <div class="grow text-sm font-sans">
                                {{ $choice['text'] }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-surface border border-line p-5 rounded-xl text-stone">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-stone" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs text-stone">
                        No questions available for this assessment. Please contact your administrator if this persists.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation Buttons -->
    @if($this->getCurrentQuestion())
        <div class="flex justify-between items-center pt-2">
            <button
                wire:click="previousQuestion"
                disabled="{{ $currentQuestionIndex === 0 }}"
                class="px-5 py-2.5 rounded-lg border border-line text-xs font-medium {{ $currentQuestionIndex === 0 ? 'opacity-40 cursor-not-allowed text-stone bg-paper' : 'text-ink bg-surface hover:bg-paper' }} transition-colors"
            >
                ← Previous
            </button>

            @if($currentQuestionIndex < count($questions) - 1)
                <button
                    wire:click="nextQuestion"
                    class="px-6 py-2.5 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors"
                >
                    Next →
                </button>
            @else
                <!-- Submit Button -->
                <button
                    wire:click="submitAssessment"
                    {{ count($answers) === 0 ? 'disabled title="Please answer at least one question"' : '' }}
                    class="px-6 py-2.5 bg-ink text-paper text-xs font-medium rounded-lg hover:bg-black transition-colors {{ count($answers) === 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                >
                    Submit assessment {{ count($answers) > 0 ? '(' . $this->getUnansweredCount() . ' unanswered)' : '' }}
                </button>
            @endif
        </div>
    @endif

    <!-- Submission Confirmation Modal (Alpine.js) -->
    <div 
        x-data="{ showModal: false, showWarning: false }"
        x-show="showModal"
        class="fixed inset-0 bg-ink/40 flex items-center justify-center z-50 p-4"
        style="display: none;"
    >
        <div class="bg-surface rounded-xl border border-line p-6 max-w-md w-full">
            <h3 class="text-base font-medium text-ink mb-2">Submit assessment?</h3>
            <p class="text-xs text-stone mb-6 leading-relaxed">
                You have answered <strong class="text-ink font-semibold">{{ count($answers) }}</strong> out of {{ count($questions) }} questions.
                
                @if(count($answers) < count($questions))
                    <br><br>
                    <span class="text-wrong font-medium">
                        You still have unanswered questions. Do you want to submit anyway?
                    </span>
                @endif
            </p>

            <div class="flex space-x-3 justify-end">
                <button 
                    wire:click="$dispatch('close-modal')"
                    class="px-4 py-2 border border-line bg-surface rounded-lg text-stone hover:text-ink hover:bg-paper text-xs font-medium"
                >
                    Continue
                </button>
                <button
                    onclick="document.querySelector('[wire\\:click=\`submitAssessment\']').click()"
                    class="px-4 py-2 bg-ink text-paper rounded-lg hover:bg-black text-xs font-medium"
                >
                    Yes, submit
                </button>
            </div>
        </div>
    </div>
</div>

@script
<script>
$wire.on('answer-selected', () => {
    // Handle answer selection feedback
});
</script>
@endscript

