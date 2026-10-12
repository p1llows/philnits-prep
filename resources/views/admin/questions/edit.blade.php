<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Edit question') }} #{{ $question->id }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="text-sm text-stone hover:text-ink">
                ← Back to questions
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full border border-[#D9D5C9] bg-surface text-accent font-mono text-xs mb-2 shadow-xs">
                    <span>ADMINISTRATION</span>
                    <span class="text-stone">·</span>
                    <span class="font-bold">EDIT QUESTION #{{ $question->id }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-ink mb-1">Edit question</h1>
                <p class="text-sm text-stone">Modify question wording, correct answer, or explanatory notes.</p>
            </div>

            <div class="bg-surface rounded-2xl p-6 md:p-8 border border-[#CFCABD] shadow-xs">
                
                <form action="{{ route('admin.questions.update', $question) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Meta Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="topic_id" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Topic *</label>
                            <select name="topic_id" id="topic_id" required class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm">
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ old('topic_id', $question->topic_id) == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }} ({{ $topic->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="difficulty" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Difficulty *</label>
                            <select name="difficulty" id="difficulty" required class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm">
                                <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Status *</label>
                            <select name="status" id="status" required class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm">
                                <option value="published" {{ old('status', $question->status) == 'published' ? 'selected' : '' }}>Published (active)</option>
                                <option value="validated" {{ old('status', $question->status) == 'validated' ? 'selected' : '' }}>Validated</option>
                                <option value="draft" {{ old('status', $question->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div>
                        <label for="question_text" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Question text *</label>
                        <textarea name="question_text" id="question_text" rows="4" required
                                  class="block w-full rounded-xl border-[#D9D5C9] text-ink font-serif shadow-xs focus:border-accent focus:ring-accent text-sm">{{ old('question_text', $question->question_text) }}</textarea>
                        @error('question_text') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Choices (A, B, C, D) -->
                    <div class="space-y-4 pt-4 border-t border-[#E3DFD5]">
                        <label class="block text-xs font-mono font-bold text-ink uppercase tracking-wider">Choices & correct answer *</label>
                        
                        @php
                            $choicesByCode = $question->choices->keyBy('code');
                        @endphp

                        @foreach(['A', 'B', 'C', 'D'] as $code)
                            @php
                                $choiceText = old('choices.' . $code, $choicesByCode[$code]->choice_text ?? $choicesByCode[$code]->text ?? '');
                            @endphp
                            <div class="flex items-center space-x-3 p-3.5 bg-paper/60 rounded-xl border border-[#E3DFD5]">
                                <input type="radio" name="correct_answer_code" value="{{ $code }}" id="correct_{{ $code }}"
                                       {{ old('correct_answer_code', $question->correct_answer_code) === $code ? 'checked' : '' }}
                                       class="h-4 w-4 text-accent border-[#D9D5C9] focus:ring-accent">
                                <label for="correct_{{ $code }}" class="font-mono font-bold text-accent w-6 text-center text-sm">{{ $code }}.</label>
                                <input type="text" name="choices[{{ $code }}]" value="{{ $choiceText }}" required
                                       class="flex-1 rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm">
                            </div>
                        @endforeach
                        @error('correct_answer_code') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Explanation -->
                    <div class="pt-4 border-t border-[#E3DFD5]">
                        <label for="explanation" class="block text-xs font-mono font-medium text-stone uppercase tracking-wider mb-1.5">Explanation / answer rationale</label>
                        <textarea name="explanation" id="explanation" rows="3"
                                  class="block w-full rounded-xl border-[#D9D5C9] text-ink shadow-xs focus:border-accent focus:ring-accent text-sm">{{ old('explanation', $question->explanation) }}</textarea>
                        @error('explanation') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between items-center pt-4 border-t border-[#E3DFD5]">
                        <button type="button" 
                                onclick="if(confirm('Delete this question permanently?')) document.getElementById('delete-form').submit();"
                                class="text-wrong hover:underline text-xs font-mono font-semibold">
                            Delete question
                        </button>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.questions.index') }}" class="px-5 py-2.5 border border-[#D9D5C9] text-stone hover:text-ink hover:bg-paper rounded-xl text-xs font-medium transition-colors shadow-xs">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-accent text-surface rounded-xl hover:bg-accent/90 text-xs font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                Update question
                            </button>
                        </div>
                    </div>

                </form>

                <form id="delete-form" action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
