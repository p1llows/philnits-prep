<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>TOPIC DETAIL</span>
                        <span class="text-stone">·</span>
                        <span class="font-bold">{{ $topic->code }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">{{ $topic->name }}</h1>
                    <p class="text-sm text-stone">Topic question review & syllabus breakdown</p>
                </div>
                <a href="{{ route('topics.practice', $topic) }}" 
                   class="inline-flex items-center justify-center space-x-2 px-5 py-2.5 bg-accent text-surface hover:bg-accent/90 text-sm font-semibold rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all shrink-0">
                    <span>Start practice mode</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Topic Header Card -->
            <div class="bg-surface border border-[#CFCABD] rounded-2xl p-6 shadow-xs">
                <h3 class="text-lg font-serif font-semibold text-ink mb-2">{{ $topic->name }}</h3>
                <p class="text-sm text-stone leading-relaxed mb-4">{{ $topic->description ?? 'No description provided.' }}</p>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-mono font-medium bg-paper border border-[#D9D5C9] text-stone">
                        {{ $questions->count() }} questions available
                    </span>
                    @if(count($mistakeIds) > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full font-mono font-medium bg-wrong-surface border border-wrong/30 text-wrong">
                            {{ count($mistakeIds) }} unresolved mistakes
                        </span>
                    @endif
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-surface border border-[#CFCABD] rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-[#E3DFD5] bg-panel flex justify-between items-center">
                    <h4 class="text-base font-serif font-semibold text-ink">Topic questions</h4>
                    <span class="text-xs font-mono text-stone">Ordered by question number</span>
                </div>

                @if($questions->count() > 0)
                    <div class="divide-y divide-[#E3DFD5]">
                        @foreach($questions as $index => $question)
                            <div class="p-6 hover:bg-paper/40 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-paper border border-[#D9D5C9] text-accent font-mono font-bold text-xs shadow-xs">
                                            #{{ $question->source_question_number ?? ($index + 1) }}
                                        </span>
                                        @if(in_array($question->id, $mistakeIds->toArray()))
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-wrong-surface border border-wrong/30 text-wrong">
                                                Needs review
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs font-mono text-stone uppercase">
                                        Difficulty: {{ ucfirst($question->difficulty ?? 'Medium') }}
                                    </span>
                                </div>

                                <!-- Question Text (Source Serif 4) -->
                                <p class="font-serif text-ink text-base mb-4 leading-relaxed">
                                    {{ $question->question_text }}
                                </p>

                                <!-- Options List -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                    @foreach($question->choices as $choice)
                                        @php $isCorrect = ($choice->code === $question->correct_answer_code); @endphp
                                        <div class="p-3.5 rounded-xl border text-sm flex items-center {{ $isCorrect ? 'bg-correct-surface/40 border-correct/40 text-correct font-medium' : 'bg-surface border-[#E3DFD5] text-ink' }}">
                                            <span class="font-mono font-bold mr-3 w-6 h-6 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $isCorrect ? 'bg-correct text-surface' : 'bg-paper border border-[#D9D5C9] text-stone' }}">
                                                @if($isCorrect) ✓ @else {{ $choice->code }} @endif
                                            </span>
                                            <span>{{ $choice->choice_text }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($question->explanation)
                                    <div class="p-4 bg-paper/60 border border-[#E3DFD5] rounded-xl text-xs text-stone leading-relaxed">
                                        <span class="font-bold text-ink uppercase tracking-wider font-mono mr-1">Explanation:</span> {{ $question->explanation }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mb-4 flex justify-center">
                            <x-mascot variant="inspect" :size="96" class="w-[72px] h-[72px] sm:w-[96px] sm:h-[96px]" />
                        </div>
                        <p class="text-xs text-stone">No published questions found for this topic yet.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

