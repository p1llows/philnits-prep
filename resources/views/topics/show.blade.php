<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-ink mb-1">{{ $topic->name }} ({{ $topic->code }})</h1>
                    <p class="text-sm text-stone">Topic question review</p>
                </div>
                <a href="{{ route('topics.practice', $topic) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-ink text-paper text-sm font-medium rounded-lg hover:bg-black transition-colors shrink-0">
                    Start practice mode →
                </a>
            </div>

            <!-- Topic Header Card -->
            <div class="bg-surface border border-line rounded-xl p-5">
                <h3 class="text-lg font-medium text-ink mb-2">{{ $topic->name }}</h3>
                <p class="text-sm text-stone leading-relaxed mb-4">{{ $topic->description ?? 'No description provided.' }}</p>
                <div class="flex items-center space-x-3 text-xs">
                    <span class="inline-flex items-center px-2.5 py-1 rounded font-medium bg-paper border border-line text-stone">
                        {{ $questions->count() }} questions available
                    </span>
                    @if(count($mistakeIds) > 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded font-medium bg-wrong-surface border border-line text-wrong">
                            {{ count($mistakeIds) }} unresolved mistakes
                        </span>
                    @endif
                </div>
            </div>

            <!-- Questions List -->
            <div class="bg-surface border border-line rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-line bg-paper/30 flex justify-between items-center">
                    <h4 class="text-base font-medium text-ink">Topic questions</h4>
                    <span class="text-xs text-stone">Ordered by question number</span>
                </div>

                @if($questions->count() > 0)
                    <div class="divide-y divide-line">
                        @foreach($questions as $index => $question)
                            <div class="p-5 hover:bg-paper/40 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-paper border border-line text-ink font-semibold text-xs">
                                            #{{ $question->source_question_number ?? ($index + 1) }}
                                        </span>
                                        @if(in_array($question->id, $mistakeIds->toArray()))
                                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-wrong-surface border border-line text-wrong">
                                                Needs review
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-stone">
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
                                        <div class="p-3 rounded-lg border text-sm flex items-center {{ $isCorrect ? 'bg-correct-surface border-line text-correct font-medium' : 'bg-surface border-line text-ink' }}">
                                            <span class="font-bold mr-2.5 w-6 h-6 rounded flex items-center justify-center text-xs shrink-0 {{ $isCorrect ? 'bg-correct text-surface' : 'bg-paper border border-line text-stone' }}">
                                                @if($isCorrect) ✓ @else {{ $choice->code }} @endif
                                            </span>
                                            <span>{{ $choice->choice_text }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if($question->explanation)
                                    <div class="p-3 bg-paper border border-line rounded-lg text-xs text-stone">
                                        <span class="font-medium text-ink">Explanation:</span> {{ $question->explanation }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-xs text-stone">No published questions found for this topic yet.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

