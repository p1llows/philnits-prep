<div x-data="{ showAllModal: false }" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-2">
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
        
        <!-- Pagination-Style 1-Row Question Bar -->
        <div class="pt-3 border-t border-line/40 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
            @php
                $total = count($questions);
                $curr = $currentQuestionIndex; // 0-indexed

                // Responsive window calculation:
                // Full list: 0-indexed.
                // We generate both 5-slot (for compact mobile) and 7-slot (for desktop), or a compact 5-slot that cleanly fits both without any horizontal scrollbar!
                // 5-slot window fits 100% of mobile screens (< 360px):
                // Start: [1] [2] [3] ... [N]
                // Middle: [1] ... [C] ... [N]
                // End: [1] ... [N-2] [N-1] [N]
                // 7-slot on sm screens:
                // Start: [1] [2] [3] [4] [5] ... [N]
                // Middle: [1] ... [C-1] [C] [C+1] ... [N]
                // End: [1] ... [N-4] [N-3] [N-2] [N-1] [N]
                
                $mobilePages = [];
                if ($total <= 5) {
                    $mobilePages = range(0, $total - 1);
                } elseif ($curr < 3) {
                    $mobilePages = [0, 1, 2, '...', $total - 1];
                } elseif ($curr > $total - 4) {
                    $mobilePages = [0, '...', $total - 3, $total - 2, $total - 1];
                } else {
                    $mobilePages = [0, '...', $curr, '...', $total - 1];
                }

                $desktopPages = [];
                if ($total <= 7) {
                    $desktopPages = range(0, $total - 1);
                } elseif ($curr < 4) {
                    $desktopPages = [0, 1, 2, 3, 4, '...', $total - 1];
                } elseif ($curr > $total - 5) {
                    $desktopPages = [0, '...', $total - 5, $total - 4, $total - 3, $total - 2, $total - 1];
                } else {
                    $desktopPages = [0, '...', $curr - 1, $curr, $curr + 1, '...', $total - 1];
                }

            @endphp

            <!-- Pagination Numbers Strip (Auto-fits mobile without horizontal overflow or scrollbars) -->
            <div class="flex items-center justify-between sm:justify-start space-x-1 sm:space-x-1.5 py-0.5 w-full sm:w-auto">
                <!-- Prev Button -->
                <button
                    type="button"
                    wire:click="previousQuestion"
                    {{ $curr === 0 ? 'disabled' : '' }}
                    class="h-8 px-2 sm:px-3 rounded-lg border border-line bg-surface text-ink hover:bg-paper disabled:opacity-30 disabled:cursor-not-allowed transition-colors font-mono text-xs flex items-center justify-center space-x-1 shadow-2xs shrink-0"
                    aria-label="Previous question"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span class="hidden sm:inline">Prev</span>
                </button>

                <!-- Mobile view: 5 items (Fits perfectly on any screen >= 320px) -->
                <div class="flex sm:hidden items-center space-x-1">
                    @foreach($mobilePages as $p)
                        @if($p === '...')
                            <div wire:key="m-ellipsis-{{ $loop->index }}" class="w-7 h-7 flex items-center justify-center text-stone font-mono select-none text-xs shrink-0">
                                <span>...</span>
                            </div>
                        @elseif(isset($questions[$p]))
                            @php
                                $qId = $questions[$p]['id'];
                                $isCurrent = ($p === $curr);
                                $isAnswered = isset($answers[$qId]);
                            @endphp
                            <button
                                type="button"
                                wire:key="m-btn-{{ $p }}"
                                wire:click="navigateTo({{ $p }})"
                                class="w-7 h-7 rounded-lg font-mono text-xs font-semibold flex items-center justify-center border transition-colors relative shrink-0 {{ $isCurrent ? 'bg-accent text-surface border-accent shadow-xs ring-2 ring-accent/20 font-bold' : ($isAnswered ? 'bg-accent-tint text-accent border-accent/30 hover:bg-accent-tint/90' : 'bg-surface text-stone border-line hover:bg-paper hover:text-ink') }}"
                                title="Question {{ $p + 1 }}"
                            >
                                <span>{{ $p + 1 }}</span>
                                @if($isAnswered && !$isCurrent)
                                    <span class="absolute bottom-0.5 w-1 h-1 rounded-full bg-accent"></span>
                                @endif
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- Desktop / Tablet view: 7 items -->
                <div class="hidden sm:flex items-center space-x-1.5">
                    @foreach($desktopPages as $p)
                        @if($p === '...')
                            <div wire:key="d-ellipsis-{{ $loop->index }}" class="w-8 h-8 flex items-center justify-center text-stone font-mono select-none text-xs shrink-0">
                                <span>...</span>
                            </div>
                        @elseif(isset($questions[$p]))
                            @php
                                $qId = $questions[$p]['id'];
                                $isCurrent = ($p === $curr);
                                $isAnswered = isset($answers[$qId]);
                            @endphp
                            <button
                                type="button"
                                wire:key="d-btn-{{ $p }}"
                                wire:click="navigateTo({{ $p }})"
                                class="w-8 h-8 rounded-lg font-mono text-xs font-semibold flex items-center justify-center border transition-colors relative shrink-0 {{ $isCurrent ? 'bg-accent text-surface border-accent shadow-xs ring-2 ring-accent/20 font-bold' : ($isAnswered ? 'bg-accent-tint text-accent border-accent/30 hover:bg-accent-tint/90' : 'bg-surface text-stone border-line hover:bg-paper hover:text-ink') }}"
                                title="Question {{ $p + 1 }}"
                            >
                                <span>{{ $p + 1 }}</span>
                                @if($isAnswered && !$isCurrent)
                                    <span class="absolute bottom-0.5 w-1 h-1 rounded-full bg-accent"></span>
                                @endif
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- Next Button -->
                <button
                    type="button"
                    wire:click="nextQuestion"
                    {{ $curr >= $total - 1 ? 'disabled' : '' }}
                    class="h-8 px-2 sm:px-3 rounded-lg border border-line bg-surface text-ink hover:bg-paper disabled:opacity-30 disabled:cursor-not-allowed transition-colors font-mono text-xs flex items-center justify-center space-x-1 shadow-2xs shrink-0"
                    aria-label="Next question"
                >
                    <span class="hidden sm:inline">Next</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>


            <!-- Right: Grid View Modal Trigger & Answered Summary -->
            <div class="flex items-center justify-between sm:justify-end space-x-2.5 text-stone font-mono text-[11px] w-full sm:w-auto pt-1 sm:pt-0 border-t sm:border-t-0 border-line/30">
                <!-- Grid View Button -->
                <button 
                    @click="showAllModal = true" 
                    type="button" 
                    class="h-8 px-3 rounded-lg border border-line bg-surface text-ink hover:bg-paper hover:border-accent/40 transition-all font-mono text-xs font-medium inline-flex items-center space-x-1.5 shadow-2xs"
                    title="View all questions in a grid map"
                >
                    <svg class="w-3.5 h-3.5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Grid View</span>
                </button>

                <span class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-lg bg-paper border border-line text-ink">
                    <span class="w-2 h-2 rounded-full bg-correct"></span>
                    <span><strong>{{ count($answers) }}</strong>/{{ count($questions) }}</span>
                </span>
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
            <div class="font-serif text-lg md:text-xl text-ink mb-6 leading-relaxed font-normal">
                <x-question-text :text="$currentQuestion['question_text']" />
            </div>

            @if(!empty($currentQuestion['image_path']))
                <div class="mb-6 rounded-xl overflow-hidden border border-line bg-paper/50 flex justify-center p-2">
                    <img src="{{ asset('storage/' . $currentQuestion['image_path']) }}" alt="Question illustration" class="max-h-96 w-auto object-contain rounded-lg" />
                </div>
            @endif

            @if(!empty($currentQuestion['code_block']))
                <div class="mb-6 rounded-xl bg-gray-900 text-gray-100 p-4 font-mono text-xs md:text-sm overflow-x-auto border border-gray-800 shadow-inner">
                    <pre><code>{{ $currentQuestion['code_block'] }}</code></pre>
                </div>
            @endif

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
                type="button"
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
                    type="button"
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

    <!-- Question Map (Grid View) Modal -->
    <div 
        x-show="showAllModal" 
        x-cloak
        @keydown.escape.window="showAllModal = false"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div 
            x-show="showAllModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="showAllModal = false"
            class="fixed inset-0 bg-ink/60 backdrop-blur-sm"
        ></div>

        <!-- Modal Dialog -->
        <div 
            x-show="showAllModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
            class="relative bg-surface rounded-2xl border border-line p-5 sm:p-6 max-w-xl w-full shadow-2xl space-y-4 z-10"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-line pb-3">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-ink">Question Map</h3>
                        <p class="text-xs text-stone">Click any question number to jump directly to it</p>
                    </div>
                </div>
                <button 
                    @click="showAllModal = false" 
                    type="button"
                    class="w-8 h-8 rounded-lg text-stone hover:text-ink hover:bg-paper text-lg font-bold flex items-center justify-center transition-colors"
                    aria-label="Close modal"
                >
                    &times;
                </button>
            </div>

            <!-- Legend & Stats -->
            <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-mono text-stone py-1 bg-paper/50 px-3 py-2 rounded-xl border border-line/60">
                <div class="flex items-center space-x-3.5">
                    <span class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-md bg-surface border border-line inline-block"></span>
                        <span>Unanswered</span>
                    </span>
                    <span class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-md bg-accent-tint border border-accent/40 inline-block"></span>
                        <span>Answered</span>
                    </span>
                    <span class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-md bg-accent inline-block"></span>
                        <span class="text-accent font-semibold">Current</span>
                    </span>
                </div>
                <div class="text-ink font-semibold">
                    {{ count($answers) }} / {{ count($questions) }}
                </div>
            </div>

            <!-- Question Numbers Grid (10 columns, clean scroll on mobile) -->
            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2 max-h-[55vh] overflow-y-auto p-1.5 border border-line/70 rounded-xl bg-paper/20">
                @foreach($questions as $index => $question)
                    @php
                        $isCurrent = ($index === $currentQuestionIndex);
                        $isAnswered = isset($answers[$question['id']]);
                    @endphp
                    <button 
                        @click="showAllModal = false"
                        wire:click="navigateTo({{ $index }})"
                        type="button"
                        class="h-9 rounded-xl font-mono text-xs font-semibold flex flex-col items-center justify-center border transition-all relative {{ $isCurrent ? 'bg-accent text-surface border-accent shadow-xs ring-2 ring-accent/30 font-bold scale-105' : ($isAnswered ? 'bg-accent-tint text-accent border-accent/40 hover:bg-accent-tint/80' : 'bg-surface text-stone border-line hover:bg-paper hover:text-ink hover:border-stone/40') }}"
                        title="Question {{ $index + 1 }}{{ $isAnswered ? ' (Answered)' : '' }}"
                    >
                        <span>{{ $index + 1 }}</span>
                        @if($isAnswered && !$isCurrent)
                            <span class="absolute bottom-1 w-1 h-1 rounded-full bg-accent"></span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Modal Footer -->
            <div class="pt-2 border-t border-line flex items-center justify-between text-xs font-mono text-stone">
                <span>{{ count($questions) - count($answers) }} questions remaining</span>
                <button 
                    @click="showAllModal = false" 
                    type="button"
                    class="px-4 py-2 rounded-xl bg-accent text-surface text-xs font-semibold hover:bg-accent/90 transition-colors shadow-2xs"
                >
                    Close
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
