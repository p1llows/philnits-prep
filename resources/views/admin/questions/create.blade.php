<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-ink leading-tight">
                {{ __('Add question') }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="text-sm text-stone hover:text-ink">
                ← Back to questions
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface rounded-xl p-6 md:p-8 border border-line">
                
                <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Meta Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="topic_id" class="block text-sm font-medium text-stone">Topic *</label>
                            <select name="topic_id" id="topic_id" required class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent">
                                <option value="">Select topic</option>
                                @foreach($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                                        {{ $topic->name }} ({{ $topic->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('topic_id') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-stone">Difficulty *</label>
                            <select name="difficulty" id="difficulty" required class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent">
                                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                            @error('difficulty') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-stone">Status *</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent">
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (active for users)</option>
                                <option value="validated" {{ old('status') == 'validated' ? 'selected' : '' }}>Validated</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-stone">Question text *</label>
                        <textarea name="question_text" id="question_text" rows="4" required
                                  placeholder="Type the examination question text here..."
                                  class="mt-1 block w-full rounded-lg border-line text-ink font-serif shadow-sm focus:border-accent focus:ring-accent">{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Choices (A, B, C, D) -->
                    <div class="space-y-4 pt-4 border-t border-line">
                        <label class="block text-sm font-semibold text-ink">Choices & correct answer *</label>
                        
                        @foreach(['A', 'B', 'C', 'D'] as $code)
                            <div class="flex items-center space-x-3 p-3 bg-paper rounded-lg border border-line">
                                <input type="radio" name="correct_answer_code" value="{{ $code }}" id="correct_{{ $code }}"
                                       {{ old('correct_answer_code', 'A') === $code ? 'checked' : '' }}
                                       class="h-4 w-4 text-accent border-line focus:ring-accent">
                                <label for="correct_{{ $code }}" class="font-bold text-ink w-6 text-center">{{ $code }}.</label>
                                <input type="text" name="choices[{{ $code }}]" value="{{ old('choices.' . $code) }}" required
                                       placeholder="Text for option {{ $code }}..."
                                       class="flex-1 rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent text-sm">
                            </div>
                        @endforeach
                        <p class="text-xs text-stone">Select the radio button next to the correct choice.</p>
                        @error('correct_answer_code') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Explanation -->
                    <div class="pt-4 border-t border-line">
                        <label for="explanation" class="block text-sm font-medium text-stone">Explanation / answer rationale</label>
                        <textarea name="explanation" id="explanation" rows="3"
                                  placeholder="Provide clear explanation for why the answer is correct..."
                                  class="mt-1 block w-full rounded-lg border-line text-ink shadow-sm focus:border-accent focus:ring-accent text-sm">{{ old('explanation') }}</textarea>
                        @error('explanation') <p class="text-wrong text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-line">
                        <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 border border-line text-stone hover:text-ink hover:bg-paper rounded-lg text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-ink text-surface rounded-lg hover:bg-stone text-sm font-medium">
                            Save question
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
