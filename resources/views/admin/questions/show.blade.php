<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                        <span>ADMINISTRATION</span>
                        <span class="text-stone">·</span>
                        <span class="font-bold">QUESTION #{{ $question->id }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Question preview</h1>
                    <p class="text-sm text-stone">Review question presentation, correct answer, and explanation.</p>
                </div>
                <div class="flex items-center space-x-3 shrink-0">
                    <a href="{{ route('admin.questions.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-surface border border-[#D9D5C9] text-accent hover:bg-paper rounded-xl text-xs font-medium transition-all shadow-xs">
                        <span>← Back to questions</span>
                    </a>
                    <a href="{{ route('admin.questions.edit', $question) }}" class="inline-flex items-center space-x-1.5 px-5 py-2 bg-accent text-surface hover:bg-accent/90 rounded-xl text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <span>Edit question</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="p-4 bg-correct-surface/50 border border-correct/30 text-correct text-xs font-mono font-medium rounded-xl flex items-center shadow-xs">
                    <span class="mr-2 font-bold">✓</span> {{ session('success') }}
                </div>
            @endif

            <!-- Question Card -->
            <div class="bg-surface rounded-2xl border border-[#CFCABD] overflow-hidden shadow-xs">
                <div class="p-6 md:p-8 bg-panel border-b border-[#E3DFD5]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-semibold bg-surface text-accent border border-[#D9D5C9]">
                                {{ $question->topic->name ?? 'General' }} ({{ $question->topic->code ?? 'N/A' }})
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-mono font-semibold {{ $question->status === 'published' ? 'bg-correct-surface/50 text-correct border border-correct/30' : 'bg-paper text-stone border border-[#D9D5C9]' }}">
                                {{ ucfirst($question->status) }}
                            </span>
                        </div>
                        <span class="text-xs font-mono text-stone uppercase">
                            Difficulty: {{ ucfirst($question->difficulty ?? 'Medium') }}
                        </span>
                    </div>

                    <h3 class="font-serif text-xl font-semibold text-ink leading-relaxed">
                        {{ $question->question_text }}
                    </h3>
                </div>

                <div class="p-6 md:p-8 space-y-3">
                    <h4 class="text-xs font-mono text-stone uppercase tracking-wider mb-2">Options</h4>
                    
                    @foreach($question->choices as $choice)
                        @php
                            $isCorrect = ($choice->code === $question->correct_answer_code);
                        @endphp
                        <div class="p-4 rounded-xl border flex items-center {{ $isCorrect ? 'bg-correct-surface/40 border-correct/40 text-correct' : 'bg-surface border-[#E3DFD5] text-ink' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-mono font-bold mr-3.5 text-xs {{ $isCorrect ? 'bg-correct text-surface' : 'bg-paper text-stone border border-[#D9D5C9]' }}">
                                {{ $choice->code }}
                            </div>
                            <div class="flex-grow font-sans text-sm">
                                {{ $choice->choice_text ?? $choice->text }}
                            </div>
                            @if($isCorrect)
                                <span class="text-xs font-mono font-bold text-correct bg-correct-surface border border-correct/30 px-3 py-1 rounded-full">
                                    Correct answer ✓
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($question->explanation)
                    <div class="p-6 bg-paper/60 border-t border-[#E3DFD5]">
                        <h4 class="text-xs font-mono font-bold text-ink uppercase tracking-wider mb-2">Explanation</h4>
                        <p class="text-xs text-stone leading-relaxed">{{ $question->explanation }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
