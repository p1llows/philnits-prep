<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Question preview') }} #{{ $question->id }}
            </h2>
            <div class="space-x-3">
                <a href="{{ route('admin.questions.edit', $question) }}" class="px-4 py-2 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
                    Edit question
                </a>
                <a href="{{ route('admin.questions.index') }}" class="text-sm text-stone hover:text-ink">
                    ← Back to questions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-correct-surface border-l-4 border-correct text-correct rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Question Card -->
            <div class="bg-surface rounded-xl border border-line overflow-hidden">
                <div class="p-6 md:p-8 bg-paper border-b border-line">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-accent-tint text-accent border border-accent/20">
                                {{ $question->topic->name ?? 'General' }} ({{ $question->topic->code ?? 'N/A' }})
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $question->status === 'published' ? 'bg-correct-surface text-correct' : 'bg-accent-tint text-accent' }}">
                                {{ ucfirst($question->status) }}
                            </span>
                        </div>
                        <span class="text-xs text-stone font-semibold">
                            Difficulty: {{ ucfirst($question->difficulty ?? 'Medium') }}
                        </span>
                    </div>

                    <h3 class="font-serif text-xl font-semibold text-ink leading-relaxed">
                        {{ $question->question_text }}
                    </h3>
                </div>

                <div class="p-6 md:p-8 space-y-3">
                    <h4 class="text-xs font-medium text-stone mb-2">Options</h4>
                    
                    @foreach($question->choices as $choice)
                        @php
                            $isCorrect = ($choice->code === $question->correct_answer_code);
                        @endphp
                        <div class="p-4 rounded-lg border flex items-center {{ $isCorrect ? 'bg-correct-surface border-correct text-correct' : 'bg-surface border-line text-ink' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold mr-3 {{ $isCorrect ? 'bg-correct text-surface' : 'bg-paper text-ink border border-line' }}">
                                {{ $choice->code }}
                            </div>
                            <div class="flex-grow font-medium text-sm">
                                {{ $choice->choice_text ?? $choice->text }}
                            </div>
                            @if($isCorrect)
                                <span class="text-xs font-semibold text-correct bg-correct-surface border border-correct/30 px-2 py-0.5 rounded-lg">
                                    Correct answer ✓
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($question->explanation)
                    <div class="p-6 bg-paper border-t border-line">
                        <h4 class="text-xs font-bold text-stone mb-1">Explanation</h4>
                        <p class="text-sm text-ink leading-relaxed">{{ $question->explanation }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
