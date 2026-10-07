<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Progress Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">Question {{ $this->getQuestionNumber() }} of {{ $this->getTotalQuestions() }}</span>
            <span class="text-sm font-medium text-gray-500">{{ $this->getProgressPercentage() }}% Completed</span>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div 
                class="bg-primary-600 h-2.5 rounded-full transition-all duration-300" 
                style="width: {{ $this->getProgressPercentage() }}%"
            ></div>
        </div>
        
        <!-- Navigation Grid (for quick jump) -->
        <div class="mt-6">
            <div class="grid grid-cols-10 gap-2">
                @foreach($questions as $index => $question)
                    <button 
                        wire:click="navigateTo({{ $index }})"
                        class="p-2 text-sm rounded-lg transition-colors {{ $index === $currentQuestionIndex ? 'bg-primary-600 text-white' : ($answers[$question['id']] ?? null ? 'bg-green-100 text-green-800 border-2 border-green-400' : 'bg-white text-gray-700 border-2 border-gray-300 hover:bg-gray-50') }}"
                    >
                        {{ $index + 1 }}
                        @if($answers[$question['id']] ?? null)
                            <svg class="w-3 h-3 mx-auto mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>
            
            <div class="mt-2 flex items-center justify-end space-x-4 text-xs text-gray-500">
                <span class="flex items-center"><span class="w-4 h-4 inline-block bg-gray-100 border-2 border-gray-300 rounded mr-1"></span>Unanswered</span>
                <span class="flex items-center"><span class="w-4 h-4 inline-block bg-green-100 border-2 border-green-400 rounded mr-1"></span>Answered</span>
                <span class="flex items-center"><span class="w-4 h-4 inline-block bg-primary-600 rounded mr-1"></span>Current</span>
            </div>
        </div>
    </div>

    <!-- Question Card -->
    @if($currentQuestion = $this->getCurrentQuestion())
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 animate-fade-in">
            <!-- Question Text -->
            <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-6 leading-relaxed">
                {{ $currentQuestion['question_text'] }}
            </h2>

            <!-- Choices -->
            <div class="space-y-3">
                @foreach($currentQuestion['choices'] as $choice)
                    @php
                        $selectedAnswer = $answers[$currentQuestion['id']] ?? null;
                    @endphp
                    <label 
                        class="block cursor-pointer {{ $selectedAnswer === $choice['code'] ? 'bg-blue-50 border-blue-300' : 'hover:bg-gray-50' }}"
                    >
                        <input 
                            type="radio" 
                            name="selected_answer_{{ $currentQuestion['id'] }}"
                            value="{{ $choice['code'] }}"
                            {{ $selectedAnswer === $choice['code'] ? 'checked' : '' }}
                            wire:click="selectAnswer({{ $currentQuestion['id'] }}, '{{ $choice['code'] }}')"
                            class="sr-only peer"
                        />
                        <div class="flex items-center p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full flex items-center justify-center mr-3 peer-checked:bg-blue-500 peer-checked:text-white">
                                {{ $choice['code'] }}
                            </div>
                            <div class="flex-grow text-gray-800">
                                {{ $choice['text'] }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No questions available for this assessment. Please contact your administrator if this persists.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation Buttons -->
    @if($this->getCurrentQuestion())
        <div class="mt-8 flex justify-between items-center">
            <button
                wire:click="previousQuestion"
                disabled="{{ $currentQuestionIndex === 0 }}"
                class="px-6 py-3 rounded-lg border-2 {{ $currentQuestionIndex === 0 ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-300 text-gray-700 hover:bg-gray-50 font-medium' }} transition-colors"
            >
                ← Previous
            </button>

            @if($currentQuestionIndex < count($questions) - 1)
                <button
                    wire:click="nextQuestion"
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors shadow-sm"
                >
                    Next →
                </button>
            @else
                <!-- Submit Button -->
                <button
                    wire:click="submitAssessment"
                    {{ count($answers) === 0 ? 'disabled title="Please answer at least one question"' : '' }}
                    {{ count($answers) > 0 ? 'class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors shadow-sm"' : 'class="px-8 py-3 bg-gray-400 text-gray-500 rounded-lg font-medium cursor-not-allowed"' }}
                >
                    Submit Assessment {{ count($answers) > 0 ? '(' . $this->getUnansweredCount() . ' unanswered)' : '' }}
                </button>
            @endif
        </div>
    @endif

    <!-- Submission Confirmation Modal (Alpine.js) -->
    <div 
        x-data="{ showModal: false, showWarning: false }"
        x-show="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit Assessment?</h3>
            <p class="text-gray-600 mb-6">
                You have answered <strong>{{ count($answers) }}</strong> out of {{ count($questions) }} questions.
                
                @if(count($answers) < count($questions))
                    <br><br>
                    <span class="text-orange-600 font-medium">
                        ⚠️ You still have unanswered questions. Do you want to submit anyway?
                    </span>
                @endif
            </p>

            <div class="flex space-x-3 justify-end">
                <button 
                    wire:click="$dispatch('close-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                >
                    Continue
                </button>
                <button
                    onclick="document.querySelector('[wire\\:click=\`submitAssessment\']').click()"
                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium"
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
    // Handle answer selection feedback
});
</script>
@endscript
